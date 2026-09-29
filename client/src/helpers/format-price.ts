export function formatPrice(cents: number): string {
  return (cents / 100).toFixed(2)
}

export function currencyPrice(cents: number): string {
  return `$${formatPrice(cents)}`
}
