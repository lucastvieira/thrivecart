export interface Product {
  code: string
  name: string
  price: number
}

export interface ProductsResponse {
  products: Product[]
}
