@extends('layouts.admin')

@section('title', 'Client Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-3">Client Management & Cancellations</h1>
            
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Client Database</h5>
                    <small class="text-muted">Manage client accounts, cancellations, and payment processing</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="clientTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Loved One</th>
                                    <th>Service Status</th>
                                    <th>Payment Status</th>
                                    <th>Total Calls</th>
                                    <th>Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                <tr id="client-row-{{ $client->id }}" class="{{ $client->status == 0 ? 'table-danger' : '' }}">
                                    <td>{{ $client->id }}</td>
                                    <td>
                                        <strong>{{ $client->name }}</strong>
                                        @if($client->status == 0)
                                            <br><small class="badge badge-danger">CANCELLED</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $client->email }}
                                        @if($client->alernate_email)
                                            <br><small class="text-muted">Alt: {{ $client->alernate_email }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $client->phone }}
                                        @if($client->cell_phone_no)
                                            <br><small class="text-muted">Cell: {{ $client->cell_phone_no }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($client->lovedones->isNotEmpty())
                                            {{ $client->lovedones->first()->phone_no }}
                                            <br><small class="text-muted">{{ $client->lovedones->first()->timezone }}</small>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($client->subscription_status == 1)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($client->payment_processing_enabled ?? 1)
                                            <span class="badge badge-primary">Enabled</span>
                                        @else
                                            <span class="badge badge-warning">Stopped</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $client->call_count }}</strong>
                                        @if($client->last_call)
                                            <br><small class="text-muted">Last: {{ $client->last_call->created_at->format('M d') }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $client->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group-vertical btn-group-sm">
                                            <button class="btn btn-info btn-sm" onclick="viewClientDetails({{ $client->id }})">
                                                <i class="fas fa-eye"></i> Details
                                            </button>
                                            
                                            @if($client->status == 1)
                                                @if($client->payment_processing_enabled ?? 1)
                                                    <button class="btn btn-warning btn-sm" onclick="togglePayment({{ $client->id }}, 'stop')">
                                                        <i class="fas fa-pause"></i> Stop Payment
                                                    </button>
                                                @else
                                                    <button class="btn btn-success btn-sm" onclick="togglePayment({{ $client->id }}, 'resume')">
                                                        <i class="fas fa-play"></i> Resume Payment
                                                    </button>
                                                @endif
                                                
                                                <button class="btn btn-danger btn-sm" onclick="cancelClient({{ $client->id }}, '{{ $client->name }}')">
                                                    <i class="fas fa-times"></i> Cancel Client
                                                </button>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    <i class="fas fa-ban"></i> Cancelled
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Client Details Modal -->
<div class="modal fade" id="clientDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Client Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="clientDetailsContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Client Modal -->
<div class="modal fade" id="cancelClientModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Cancel Client</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel <strong id="cancelClientName"></strong>?</p>
                <p class="text-danger">This will:</p>
                <ul class="text-danger">
                    <li>Stop all call services immediately</li>
                    <li>Disable their account</li>
                    <li>Mark them as cancelled in the system</li>
                </ul>
                <div class="form-group">
                    <label for="cancellationReason">Reason for cancellation:</label>
                    <textarea class="form-control" id="cancellationReason" rows="3" placeholder="Enter reason for cancellation..."></textarea>
                </div>
                <input type="hidden" id="cancelClientId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmCancelClient()">Yes, Cancel Client</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('#clientTable').DataTable({
        "pageLength": 25,
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            { "orderable": false, "targets": -1 }
        ]
    });
});

function viewClientDetails(clientId) {
    $('#clientDetailsModal').modal('show');
    
    $.get(`/admin/client-details/${clientId}`)
        .done(function(data) {
            let html = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Personal Information</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Name:</strong></td><td>${data.name}</td></tr>
                            <tr><td><strong>Email:</strong></td><td>${data.email}</td></tr>
                            <tr><td><strong>Phone:</strong></td><td>${data.phone || 'Not set'}</td></tr>
                            <tr><td><strong>Cell:</strong></td><td>${data.cell_phone_no || 'Not set'}</td></tr>
                            <tr><td><strong>Alt Email:</strong></td><td>${data.alternate_email || 'Not set'}</td></tr>
                            <tr><td><strong>Alt Phone:</strong></td><td>${data.alternate_phone_no || 'Not set'}</td></tr>
                            <tr><td><strong>Registered:</strong></td><td>${new Date(data.created_at).toLocaleDateString()}</td></tr>
                            <tr><td><strong>Status:</strong></td><td>${data.status == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Cancelled</span>'}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Service Information</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Total Calls:</strong></td><td>${data.total_calls}</td></tr>
                            <tr><td><strong>Service Status:</strong></td><td>${data.service_status == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>'}</td></tr>
                        </table>
                        
                        ${data.loved_ones.length > 0 ? `
                            <h6>Loved One Information</h6>
                            <table class="table table-sm">
                                <tr><td><strong>Phone:</strong></td><td>${data.loved_ones[0].phone_no}</td></tr>
                                <tr><td><strong>Timezone:</strong></td><td>${data.loved_ones[0].timezone}</td></tr>
                            </table>
                        ` : '<p>No loved one information set</p>'}
                    </div>
                </div>
                
                ${data.recent_calls.length > 0 ? `
                    <hr>
                    <h6>Recent Calls</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Duration</th>
                                    <th>Answered By</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.recent_calls.map(call => `
                                    <tr>
                                        <td>${new Date(call.created_at).toLocaleString()}</td>
                                        <td><span class="badge badge-${call.call_status === 'completed' ? 'success' : 'secondary'}">${call.call_status}</span></td>
                                        <td>${call.call_duration || 'N/A'}</td>
                                        <td>${call.answered_by || 'N/A'}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                ` : '<p>No recent calls</p>'}
            `;
            
            $('#clientDetailsContent').html(html);
        })
        .fail(function() {
            $('#clientDetailsContent').html('<div class="alert alert-danger">Error loading client details</div>');
        });
}

function cancelClient(clientId, clientName) {
    $('#cancelClientId').val(clientId);
    $('#cancelClientName').text(clientName);
    $('#cancellationReason').val('');
    $('#cancelClientModal').modal('show');
}

function confirmCancelClient() {
    const clientId = $('#cancelClientId').val();
    const reason = $('#cancellationReason').val();
    
    if (!reason.trim()) {
        Swal.fire('Error', 'Please provide a reason for cancellation', 'error');
        return;
    }
    
    $.post('/admin/cancel-client', {
        client_id: clientId,
        reason: reason,
        _token: '{{ csrf_token() }}'
    })
    .done(function(response) {
        if (response.status) {
            $('#cancelClientModal').modal('hide');
            Swal.fire('Success', response.message, 'success').then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Error', response.message, 'error');
        }
    })
    .fail(function() {
        Swal.fire('Error', 'Failed to cancel client', 'error');
    });
}

function togglePayment(clientId, action) {
    const actionText = action === 'stop' ? 'stop' : 'resume';
    
    Swal.fire({
        title: `${actionText.charAt(0).toUpperCase() + actionText.slice(1)} Payment Processing?`,
        text: `Are you sure you want to ${actionText} payment processing for this client?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: action === 'stop' ? '#dc3545' : '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Yes, ${actionText}!`
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('/admin/toggle-payment', {
                client_id: clientId,
                action: action,
                _token: '{{ csrf_token() }}'
            })
            .done(function(response) {
                if (response.status) {
                    Swal.fire('Success', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            })
            .fail(function() {
                Swal.fire('Error', 'Failed to update payment status', 'error');
            });
        }
    });
}
</script>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<style>
.btn-group-vertical .btn {
    margin-bottom: 2px;
}
.table-danger {
    background-color: #f8d7da !important;
}
</style>
@endsection