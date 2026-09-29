import { currencyPrice } from "~/helpers/format-price"
import type { Product as ProductData } from "~/types/products"

interface ProductProps {
  product: ProductData
}

export default function ProductItem({ product }: ProductProps) {
  return (
    <article className="overflow-hidden rounded-2xl border border-neutral-200 bg-white">
      <div className="h-2" style={{ backgroundColor: product.color }} />
      <div className="space-y-2 p-6">
        <p className="text-sm font-medium tracking-wide text-neutral-600">
          {product.code}
          </p>
        <h2 className="text-xl font-semibold text-neutral-900">
          {product.name}
        </h2>
        <p className="text-lg font-medium tabular-nums text-neutral-900">
          {currencyPrice(product.price)}
        </p>
      </div>
    </article>
  )
}
