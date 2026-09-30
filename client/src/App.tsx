import { useEffect, useState } from 'react';
import type { IProduct } from '~/types/products';
import type { ICartItem } from '~/types/cart';
import ProductItem from '~/components/product-item';
import Cart from '~/components/cart';
import { getCartCalculate, getProducts } from '~/services/api';
import ProductSkeleton from '~/components/product-skeleton';

export default function App() {
  const [products, setProducts] = useState<IProduct[]>([]);
  const [productsLoading, setProductsLoading] = useState(true);

  const [cartItems, setCartItems] = useState<ICartItem[]>([]);
  const [cartLoading, setCartLoading] = useState(false);

  const [total, setTotal] = useState(0);
  const [shipping, setShipping] = useState(0);

  const fetchProducts = async () => {
    setProductsLoading(true);
    try {
      const res = await getProducts();
      setProducts(res.data.products);
    } catch (error) {
      console.error(error);
    } finally {
      setProductsLoading(false);
    }
  };

  const fetchCartCalculate = async (items: ICartItem[]) => {
    setCartLoading(true);
    try {
      const { data } = await getCartCalculate(items);
      setTotal(data.total);
      setShipping(data.shipping);
    } catch (error) {
      console.error(error);
    } finally {
      setCartLoading(false);
    }
  };

  const handleAddToCart = (product: IProduct) => {
    const idx = cartItems.findIndex(
      (item) => item.product.code === product.code
    );
    if (idx === -1) {
      const newCartItems = [
        ...cartItems,
        { product, quantity: 1, total: product.price },
      ];
      setCartItems(newCartItems);
      return;
    }

    const newCartItems = [...cartItems];
    newCartItems[idx].quantity++;
    newCartItems[idx].total = product.price * newCartItems[idx].quantity;
    setCartItems(newCartItems);
  };

  useEffect(() => {
    fetchCartCalculate(cartItems);
  }, [cartItems]);

  useEffect(() => {
    fetchProducts();
  }, []);

  return (
    <div className="mx-auto flex min-h-screen w-full max-w-5xl flex-col gap-8 px-4 py-12">
      <h1 className="text-3xl font-semibold tracking-tight text-neutral-900">
        Acme Widget Co
      </h1>
      {productsLoading && <ProductSkeleton />}
      {!productsLoading && (
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
          {products.map((product) => (
            <ProductItem
              key={product.code}
              product={product}
              onAddToCart={handleAddToCart}
            />
          ))}
        </div>
      )}
      <Cart
        cartItems={cartItems}
        setCartItems={setCartItems}
        cartLoading={cartLoading}
        total={total}
        shipping={shipping}
      />
    </div>
  );
}
