@extends('layouts.admin')

@section('title', 'News')

@section('content')
<div class="container-fluid">
    <!-- Admin Header -->
    {{-- <div class="row">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                    <i class="fa fa-shield-alt"></i> Team Admin
                </a>
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="{{ route('admin.news.analytics') }}">
                        <i class="fa fa-chart-bar"></i> Analytics
                    </a>
                    <a class="nav-link" href="{{ route('admin.trivia.index') }}">
                        <i class="fa fa-brain"></i> Trivia
                    </a>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fa fa-user"></i> Admin
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.logout') }}">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div> --}}
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fa fa-newspaper text-primary"></i> News Management</h2>
                    <p class="text-muted">Manage team news and announcements</p>
                </div>
                <div>
                    <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Create News
                    </a>
                    {{-- <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#bulkActionsModal">
                        <i class="fa fa-tasks"></i> Bulk Actions
                    </button> --}}
                </div>
            </div>

            <!-- Filter & Search -->
            {{-- <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="game_result">Game Result</option>
                                <option value="trade">Trade</option>
                                <option value="press_release">Press Release</option>
                                <option value="player_stats">Player Stats</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search news...">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div> --}}

            <!-- News Table -->
            <div class="card shadow">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">All News Posts</h5>
                        {{-- <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll">
                            <label class="form-check-label" for="selectAll">Select All</label>
                        </div> --}}
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">
                                    <input type="checkbox" class="form-check-input" id="masterCheck">
                                </th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Views</th>
                                <th>Created</th>
                                <th width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($news as $item)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input news-check" value="{{ $item->id }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->featured_image)
                                            <img src="{{ asset('storage/news/' . $item->featured_image) }}" alt="News" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <strong>{{ Str::limit($item->title, 50) }}</strong>
                                            @if($item->featured)
                                                <i class="fa fa-star text-warning ms-1" title="Featured"></i>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $item->post_type)) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $item->status == 'published' ? 'success' : ($item->status == 'draft' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>
                                    <i class="fa fa-eye text-muted"></i> {{ number_format($item->views_count) }}
                                </td>
                                <td>
                                    <small class="text-muted">{{ date('M d, Y', strtotime($item->created_at)) }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm gap-2" role="group">
                                        {{-- <a href="{{ route('admin.news.show', $item->id) }}" class="btn btn-outline-info" title="View">
                                            <i class="fa fa-eye"></i>
                                        </a> --}}
                                        <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.news.destroy', $item->id) }}" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this news?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
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

    <!-- Bulk Actions Modal -->
    {{-- <div class="modal fade" id="bulkActionsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.news.bulk-action') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Bulk Actions</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Select Action</label>
                            <select name="action" class="form-select" required>
                                <option value="">Choose action...</option>
                                <option value="publish">Publish Selected</option>
                                <option value="unpublish">Unpublish Selected</option>
                                <option value="feature">Mark as Featured</option>
                                <option value="unfeature">Remove Featured</option>
                                <option value="delete">Delete Selected</option>
                            </select>
                        </div>
                        <input type="hidden" name="selected_items" id="selectedItems">
                        <p class="text-muted">Selected items: <span id="selectedCount">0</span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Apply Action</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
{{-- </div> --}}
<script>
    // Bulk selection functionality
    document.getElementById('masterCheck').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.news-check');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    document.querySelectorAll('.news-check').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    function updateSelectedCount() {
        const checked = document.querySelectorAll('.news-check:checked');
        const count = checked.length;
        document.getElementById('selectedCount').textContent = count;

        const ids = Array.from(checked).map(cb => cb.value);
        document.getElementById('selectedItems').value = JSON.stringify(ids);
    }
</script>
@endsection
