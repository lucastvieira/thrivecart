import type { IProduct } from "./products"

export interface ICartItem {
  product: IProduct
  quantity: number
  total: number
}

export interface ICartCalculateResponse {
  total: number
  shipping: number
}
