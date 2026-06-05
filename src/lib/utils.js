/**
 * A simple utility for conditionally joining classNames together.
 */
export function cn(...classes) {
  return classes.filter(Boolean).join(' ');
}
