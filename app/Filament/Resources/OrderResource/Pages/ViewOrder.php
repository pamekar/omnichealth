<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Carbon\Carbon;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\OrderLog;
use App\Models\OrdersItem;
use App\Models\Product;
use Filament\Forms;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendQuote;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected static string $view = 'filament-ecommerce::orders.show';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('send_quote')
                ->label('Send Quote')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->hidden(fn() => config('filament-ecommerce.enable_pricing'))
                ->form([
                    Forms\Components\Repeater::make('items')
                        ->hiddenLabel()
                        ->schema([
                            Forms\Components\Hidden::make('id'),
                            Forms\Components\TextInput::make('product_name')
                                ->label('Product')
                                ->disabled(),
                            Forms\Components\TextInput::make('qty')
                                ->label('Qty')
                                ->disabled()
                                ->numeric(),
                            Forms\Components\TextInput::make('price')
                                ->label('Unit Price')
                                ->required()
                                ->numeric(),
                        ])
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false)
                        ->columns(3)
                ])
                ->fillForm(fn($record) => [
                    'items' => $record->ordersItems->map(fn($item) => [
                        'id' => $item->id,
                        'product_name' => $item->product->name ?? 'Unknown Product',
                        'qty' => $item->qty,
                        'price' => $item->price > 0 ? $item->price : ($item->product->price ?? 0),
                    ])->toArray()
                ])
                ->action(function($record, array $data){
                    $total = 0;
                    foreach($data['items'] as $itemData) {
                        $item = \App\Models\OrdersItem::find($itemData['id']);
                        if ($item) {
                            $price = floatval($itemData['price']);
                            $totalItem = $price * $item->qty;
                            $item->update([
                                'price' => $price,
                                'total' => $totalItem,
                            ]);
                            $total += $totalItem;
                        }
                    }

                    $record->update([
                        'total' => $total,
                        'status' => 'prepared',
                    ]);

                    $pdf = Pdf::loadView('pdf.order', ['order' => $record]);

                    $recipient = $record->email ?? ($record->account ? $record->account->email : null);

                    if ($recipient) {
                         try {
                             Mail::to($recipient)->send(new SendQuote($record, $pdf));

                             Notification::make()
                                ->title('Quote Sent')
                                ->body('The quote has been sent to ' . $recipient)
                                ->success()
                                ->send();
                         } catch (\Exception $e) {
                             Notification::make()
                                ->title('Error sending email')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                         }
                    } else {
                         Notification::make()
                            ->title('Error')
                            ->body('Customer email not found.')
                            ->danger()
                            ->send();
                    }
                }),
            Actions\Action::make('print')
                ->icon('heroicon-o-printer')
                ->label(trans('filament-ecommerce::messages.orders.actions.print'))
                ->openUrlInNewTab()
                ->url(route('order.print', $this->getRecord()->id)),
            Actions\DeleteAction::make()->icon('heroicon-o-trash'),
            Actions\EditAction::make()->icon('heroicon-o-pencil-square')->color('warning'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['items'] = $this->getRecord()->ordersItems->toArray();
        return parent::mutateFormDataBeforeFill($data); // TODO: Change the autogenerated stub
    }
}
