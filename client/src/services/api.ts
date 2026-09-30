import axios, { type AxiosResponse } from 'axios'
import type { ICartItem, ICartCalculateResponse } from '~/types/cart'
import type { IProductsResponse } from '~/types/products'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
})

export function getProducts(): Promise<AxiosResponse<IProductsResponse>> {
  return api.get<IProductsResponse>('/api/products')
}

export function getCartCalculate(items: ICartItem[]): Promise<AxiosResponse<ICartCalculateResponse>> {
  return api.post<ICartCalculateResponse>('/api/cart/calculate', { items })
}

export default api
