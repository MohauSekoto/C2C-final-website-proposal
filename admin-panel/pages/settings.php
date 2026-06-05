<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_admin();

$page_title = 'Platform Settings';
include __DIR__ . '/../includes/header.php';
?>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">Commission Tiers</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">Update the percentage cut KasiBuy takes per tier. Changes apply to future orders only.</p>
                <form action="#" method="POST" onsubmit="alert('Settings saved (Simulation)'); return false;">
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold">Standard Tier</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="number" class="form-control" value="10.0" step="0.1">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold">Silver Tier</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="number" class="form-control" value="7.5" step="0.1">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold">Gold Tier</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="number" class="form-control" value="5.0" step="0.1">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold">Platinum Tier</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="number" class="form-control" value="3.0" step="0.1">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Save Commission Rates</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">System Preferences</h5>
            </div>
            <div class="card-body">
                <form action="#" method="POST" onsubmit="alert('Settings saved (Simulation)'); return false;">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Platform Name</label>
                        <input type="text" class="form-control" value="KasiBuy">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Support Email</label>
                        <input type="email" class="form-control" value="support@kasibuy.co.za">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Escrow Holding Period (Days)</label>
                        <input type="number" class="form-control" value="3">
                        <div class="form-text">Number of days to hold funds after delivery before automatic release.</div>
                    </div>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="maintenanceMode">
                        <label class="form-check-label text-danger fw-bold" for="maintenanceMode">Enable Maintenance Mode</label>
                        <div class="form-text">Will show a "be right back" page to all public visitors.</div>
                    </div>
                    
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">Save Preferences</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
