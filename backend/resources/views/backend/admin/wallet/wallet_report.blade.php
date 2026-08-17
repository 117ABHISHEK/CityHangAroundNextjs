<style>
    @media (max-width: 510px) {
        .pagination {
            font-size: 0.75rem !important;
            padding: 0.25rem !important;
        }

        .pagination .page-link {
            padding: 0.25rem 0.5rem !important;
        }

        .pagination .page-item {
            margin: 0 1px !important;
        }
    }
    @media (max-width: 395px) {
        .pagination {
            font-size: 0.5rem !important;
            padding: 0.25rem !important;
        }

        .pagination .page-link {
            padding: 0.15rem 0.4rem !important;
        }

      
    }
</style>

<div class="container mt-4">
    <div class="card shadow-lg p-4">
        <h4 class="mb-3 text-primary">
            <i class="fa-solid fa-wallet"></i> Admin - Wallet Report
        </h4>

        <!-- User Search Filter -->
        <div class="mb-3 position-relative">
            <input type="text" id="userSearch" class="form-control" placeholder="Search User..." autocomplete="off">
        </div>

        <div class="table-responsive">
            <table class="table  table-hover align-middle">
                <thead >
                    <tr>
                        <th>User</th>
                        <th>Wallet Balance (₹)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="walletTable">
                    @foreach ($users as $user)
                    <tr class="user-row">
                        <td>
                            <a href="{{ route('admin.wallet.transactions', $user->id) }}" class="text-black fw-normal">
                                <i class="fa-solid fa-user"></i> <span class="user-name">{{ $user->name }}</span>
                            </a>
                        </td>
                        <td>
                            <span class="badge text-black fs-6">₹{{ number_format($user->wallet_balance, 2) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.wallet.transactions', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-list"></i> View Transactions
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Live Search Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#userSearch').on('keyup', function () {
            let searchText = $(this).val().toLowerCase();

            $('.user-row').each(function () {
                let userName = $(this).find('.user-name').text().toLowerCase();
                
                if (userName.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>
