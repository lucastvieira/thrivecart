export interface IProduct {
  code: string
  name: string
  price: number
  color: string
}

export interface IProductsResponse {
  products: IProduct[]
}
