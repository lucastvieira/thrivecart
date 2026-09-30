import Skeleton from './skeleton';

export default function ProductSkeleton() {
  return (
    <div
      className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3"
      aria-busy="true"
      aria-label="Loading products"
    >
      {Array.from({ length: 3 }, (_, index) => (
        <article
          key={index}
          className="overflow-hidden rounded-2xl border border-neutral-200 bg-white"
        >
          <Skeleton className="h-2 rounded-none!" />
          <div className="space-y-2 p-6">
            <Skeleton className="h-4 w-16" />
            <Skeleton className="h-6 w-40" />
            <Skeleton className="h-5 w-20" />
            <Skeleton className="h-8 w-24" />
          </div>
        </article>
      ))}
    </div>
  );
}
