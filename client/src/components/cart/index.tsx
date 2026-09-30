import type { ICartItem } from '~/types/cart';
import CartItem from './cart-item';
import Skeleton from '~/components/skeleton';
import { currencyPrice } from '~/helpers/format-price';

interface CartProps {
  cartItems: ICartItem[];
  setCartItems: (items: ICartItem[]) => void;
  cartLoading: boolean;
  total: number;
  shipping: number;
}

export default function Cart({
  cartItems,
  setCartItems,
  cartLoading,
  total,
  shipping,
}: CartProps) {
  const handleRemove = (productCode: string) => {
    setCartItems(cartItems.filter((item) => item.product.code !== productCode));
  };

  return (
    <section className="flex flex-col gap-3">
      <h2 className="text-2xl font-semibold tracking-tight text-neutral-900">
        Cart
      </h2>
      {cartItems && cartItems.length > 0 ? (
        <div className="flex flex-col gap-3">
          {cartItems.map((item) => (
            <CartItem
              key={item.product.code}
              item={item}
              onRemove={handleRemove}
            />
          ))}

          <div className="flex justify-between" aria-busy={cartLoading}>
            <p className="text-sm text-neutral-600">Shipping</p>
            {cartLoading ? (
              <Skeleton className="h-4 w-14" />
            ) : (
              <p className="text-sm font-medium text-neutral-900">
                {currencyPrice(shipping)}
              </p>
            )}
          </div>

          <div className="flex justify-between" aria-busy={cartLoading}>
            <p className="text-sm text-neutral-600">Total</p>
            {cartLoading ? (
              <Skeleton className="h-4 w-16" />
            ) : (
              <p className="text-sm font-medium text-neutral-900">
                {currencyPrice(total)}
              </p>
            )}
          </div>
        </div>
      ) : (
        <p className="rounded-2xl border border-dashed border-neutral-200 px-6 py-8 text-center text-neutral-600">
          No items in cart
        </p>
      )}
    </section>
  );
}
