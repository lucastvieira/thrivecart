export interface Product {
  code: string
  name: string
  price: number
  color: string
}

export interface ProductsResponse {
  products: Product[]
}
