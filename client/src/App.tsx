import { getProducts } from "~/services/api"
import { useState, useEffect } from "react"
import type { ProductsResponse } from "~/types/products"

export default function App() {
  const [loading, setLoading] = useState(true)
  const [products, setProducts] = useState<ProductsResponse['products']>([])

  useEffect(() => {
    setLoading(true)
    getProducts().then((res) => {
      setProducts(res.data.products)
      setLoading(false)
    })
  }, [])

  return (
    <div className="app">
      <h1>ThriveCart</h1>
      {loading && <p>Loading...</p>}
      {products && <p>{products.map((product) => product.name).join(', ')}</p>}
    </div>
  )
}
