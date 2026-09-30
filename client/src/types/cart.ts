import type { IProduct } from "./products"

export interface ICartItem {
  product: IProduct
  quantity: number
  total: number
}

export interface ICartCalculateResponse {
  subtotal: number
  total: number
  shipping: number
}
