import Link from 'next/link';

export default function CategoryCard({ id, name, description }) {
  return (
    <Link href={`/category/${id}`} className="block group">
      <div className="p-8 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 flex flex-col items-center text-center">
        <h3 className="text-xl font-bold text-zinc-900 dark:text-zinc-50 mb-3 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{name}</h3>
        <p className="text-zinc-500 dark:text-zinc-400 text-sm leading-relaxed">{description}</p>
      </div>
    </Link>
  );
}
