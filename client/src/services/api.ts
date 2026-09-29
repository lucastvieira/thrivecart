import axios, { type AxiosResponse } from 'axios'
import type { ProductsResponse } from '~/types/products'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
})

export function getProducts(): Promise<AxiosResponse<ProductsResponse>> {
  return api.get<ProductsResponse>('/api/products')
}

export default api
