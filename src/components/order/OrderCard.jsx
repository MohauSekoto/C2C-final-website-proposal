import Link from 'next/link';

export default function OrderCard({ id, orderNumber, date, status, total }) {
  const getStatusBadge = (status) => {
    switch (status) {
      case 'completed': return 'bg-success';
      case 'processing': return 'bg-primary';
      case 'pending': return 'bg-warning text-dark';
      case 'cancelled': return 'bg-danger';
      default: return 'bg-secondary';
    }
  };

  return (
    <div className="card kasibuy-card border-0 shadow-sm mb-3">
      <div className="card-body d-flex justify-content-between align-items-center">
        <div>
          <h6 className="fw-bold mb-1">Order #{orderNumber}</h6>
          <p className="text-muted small mb-0">Placed on {new Date(date).toLocaleDateString()}</p>
        </div>
        <div className="text-end">
          <div className="fw-bold mb-1">R {Number(total).toFixed(2)}</div>
          <span className={`badge ${getStatusBadge(status)}`}>{status.toUpperCase()}</span>
        </div>
      </div>
      <div className="card-footer bg-white border-top-0 pt-0 text-end">
        <Link href={`/order/${id}`} className="btn btn-sm btn-link text-decoration-none p-0">
          View Details &rarr;
        </Link>
      </div>
    </div>
  );
}
