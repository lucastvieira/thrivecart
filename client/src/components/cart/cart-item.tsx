import { currencyPrice } from '~/helpers/format-price';
import type { ICartItem } from '~/types/cart';

interface CartItemProps {
  item: ICartItem;
  onRemove: (productCode: string) => void;
}

export default function CartItem({ item, onRemove }: CartItemProps) {
  return (
    <article className="flex items-center gap-4 overflow-hidden rounded-2xl border border-neutral-200 bg-white p-4">
      <div
        className="h-10 w-1 shrink-0 rounded-full"
        style={{ backgroundColor: item.product.color }}
      />
      <div className="min-w-0 flex-1">
        <h2 className="truncate text-base font-semibold text-neutral-900">
          {item.product.name}
        </h2>
        <p className="text-sm tabular-nums text-neutral-600">
          {currencyPrice(item.product.price)} each
        </p>
      </div>
      <p className="text-sm tabular-nums text-neutral-600">× {item.quantity}</p>
      <p className="w-20 text-right text-base font-medium tabular-nums text-neutral-900">
        {currencyPrice(item.total)}
      </p>
      <button
        type="button"
        onClick={() => onRemove(item.product.code)}
        className="cursor-pointer rounded-lg border border-neutral-200 px-3 py-1.5 text-sm text-neutral-700 hover:bg-neutral-50"
      >
        Remove
      </button>
    </article>
  );
}
