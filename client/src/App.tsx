import { getProducts } from "~/services/api"
import { useState, useEffect } from "react"
import type { Product } from "~/types/products"
import ProductItem from "~/components/product-item"

export default function App() {
  const [loading, setLoading] = useState(true)
  const [products, setProducts] = useState<Product[]>([])

  async function fetchProducts() {
    setLoading(true)
    try {
      const res = await getProducts()
      setProducts(res.data.products)
    } catch (error) {
      console.error(error)
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    fetchProducts()
  }, [])

  return (
    <div className="mx-auto flex min-h-screen w-full max-w-5xl flex-col gap-8 px-4 py-12">
      <h1 className="text-3xl font-semibold tracking-tight text-neutral-900">
        Acme Widget Co
      </h1>
      {loading && <p className="text-neutral-600">Loading...</p>}
      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        {products.map((product) => (
          <ProductItem key={product.code} product={product} />
        ))}
      </div>
    </div>
  )
}
