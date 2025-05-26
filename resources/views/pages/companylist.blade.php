@extends('layout.master')
@section('content')
<style>  

.text-primary{
  color: rgb(57 192 116) !important ;
}

</style>
<section class="dashboard-wrap py-3 bg-light">
  <div class="container">
    <div class="row mb-4 text-center">
      <div class="col-12 ">
        <h2 class="fw-bold text-primary display-5">Companies</h2>
        <hr class="w-25 mx-auto border-3 border-primary">
      </div>
    </div>

    <div class="row g-4">
      @foreach ($companys as $company)
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-lg border-0 rounded-4 h-100 hover-scale">
          <div class="card-header bg-primary bg-opacity-10 rounded-top-4">
            <h5 class="card-title fw-bold text-primary mb-0">
              {{ $company->name }} {{ $company->name_prefix }}
            </h5>
          </div>
          <div class="card-body d-flex flex-column">
            <ul class="list-unstyled mb-3 flex-grow-1">
              <li class="mb-2"><strong>Type Of Entity:</strong> <span class="text-secondary">{{ $company->type_of_entity }}</span></li>
              <li class="mb-2"><strong>ROC:</strong> <span class="text-secondary">{{ $company->roc }}</span></li>
              <li class="mb-2"><strong>Year of Incorporation:</strong> <span class="text-secondary">{{ $company->year_of_incorporation }}</span></li>
              <li class="mb-2"><strong>Industry:</strong> <span class="text-secondary">{{ $company->industry }}</span></li>
              <li class="mb-2"><strong>Ask Price:</strong> <span class="text-success fw-semibold">₹{{ number_format($company->ask_price_amount ?? 0) }} {{ $company->ask_price_unit ?? 'Rupees' }}/month</span></li>
              <li class="mb-2">
                <strong>Status:</strong> 
                @if(strtolower($company->status ?? '') === 'active')
                  <span class="badge bg-success text-uppercase">Active</span>
                @elseif(strtolower($company->status ?? '') === 'inactive')
                  <span class="badge bg-danger text-uppercase">Inactive</span>
                @else
                  <span class="badge bg-secondary text-uppercase">{{ $company->status ?? 'N/A' }}</span>
                @endif
              </li>

              @if(isset($company->buyer_status) && $company->buyer_status == 'inactive')
              <li class="mt-3">
                <a href="{{ route('user.buyer.company.removefrominterested', $company->id) }}" class="btn btn-outline-danger btn-sm w-100 fw-semibold">
                  Not Interested
                </a>
              </li>
              @endif
            </ul>

            <div id="moreDetails{{ $company->id }}" class="collapse border-top pt-3 mt-auto">
              <ul class="list-unstyled small text-muted">
                <li><strong>Have GST?:</strong> {{ $company->have_gst ?? 'N/A' }}</li>
                <li><strong>No. of Directors:</strong> {{ $company->no_of_directors }}</li>
                <li><strong>No. of Promoters:</strong> {{ $company->no_of_promoters }}</li>
                <li><strong>Activity Code:</strong> {{ $company->activity_code ?? 'N/A' }}</li>
                <li><strong>Authorised Capital:</strong> ₹{{ number_format($company->authorized_capital_amount ?? $company->authorised_capital_amount ?? 0) }} {{ $company->authorized_capital_unit ?? 'Rupees' }}</li>
                <li><strong>Paid-up Capital:</strong> ₹{{ number_format($company->paid_up_capital_amount ?? $company->paidup_capital_amount ?? 0) }} {{ $company->paid_up_capital_unit ?? 'Rupees' }}</li>
                <li><strong>Demat Shareholding:</strong> {{ $company->demat_shareholding ?? 'N/A' }}%</li>
                <li><strong>Physical Shareholding:</strong> {{ $company->physical_shareholding ?? 'N/A' }}%</li>
                <li><strong>Promoters Shareholding:</strong> {{ $company->promoters_holding ?? 'N/A' }}%</li>
                <li><strong>Transferable Shareholding:</strong> {{ $company->transferable_holding ?? 'N/A' }}%</li>
                <li><strong>Public Shareholding:</strong> {{ $company->public_holding ?? 'N/A' }}%</li>
                <li><strong>Current Market Price:</strong> ₹{{ $company->current_market_price ?? 'N/A' }}</li>
                <li><strong>52 Weeks High:</strong> ₹{{ $company->high_52_weeks ?? 'N/A' }}</li>
                <li><strong>52 Weeks Low:</strong> ₹{{ $company->low_52_weeks ?? 'N/A' }}</li>
                <li><strong>Market Capitalization:</strong> ₹{{ number_format($company->market_capitalization_amount ?? 0) }} {{ $company->market_capitalization_unit ?? 'Rupees' }}</li>
                <li><strong>Trading Conditions:</strong> {{ $company->trading_conditions ?? 'N/A' }}</li>
                <li><strong>Acquisition Method:</strong> {{ $company->acquisition_method ?? 'N/A' }}</li>
                <li><strong>Face Value:</strong> ₹{{ $company->face_value ?? 'N/A' }}</li>
                <li><strong>Type of NBFC:</strong> {{ $company->type_of_NBFC ?? 'N/A' }}</li>
                <li><strong>Size of NBFC:</strong> {{ $company->size_of_NBFC ?? 'N/A' }}</li>
                <li><strong>Turnover (2025):</strong> ₹{{ number_format($company->turnover_amount1 ?? 0) }} {{ $company->turnover_unit1 ?? 'Rupees' }}</li>
                <li><strong>Turnover (2024):</strong> ₹{{ number_format($company->turnover_amount2 ?? 0) }} {{ $company->turnover_unit2 ?? 'Rupees' }}</li>
                <li><strong>Profit (2025):</strong> ₹{{ number_format($company->profit_amount1 ?? 0) }} {{ $company->profit_unit1 ?? 'Rupees' }}</li>
                <li><strong>Profit (2024):</strong> ₹{{ number_format($company->profit_amount2 ?? 0) }} {{ $company->profit_unit2 ?? 'Rupees' }}</li>
                <li><strong>Net Worth:</strong> ₹{{ number_format($company->net_worth_amount ?? 0) }} {{ $company->net_worth_unit ?? 'Rupees' }}</li>
                <li><strong>Reserve:</strong> ₹{{ number_format($company->reserve_amount ?? 0) }} {{ $company->reserve_unit ?? 'Rupees' }}</li>
                <li><strong>Secured Creditors:</strong> ₹{{ number_format($company->secured_creditors_amount ?? 0) }} {{ $company->secured_creditors_unit ?? 'Rupees' }}</li>
                <li><strong>Unsecured Creditors:</strong> ₹{{ number_format($company->unsecured_creditors_amount ?? 0) }} {{ $company->unsecured_creditors_unit ?? 'Rupees' }}</li>
                <li><strong>Land & Building:</strong> ₹{{ number_format($company->land_building_amount ?? 0) }} {{ $company->land_building_unit ?? 'Rupees' }}</li>
                <li><strong>Plant & Machinery:</strong> ₹{{ number_format($company->plant_machinery_amount ?? 0) }} {{ $company->plant_machinery_unit ?? 'Rupees' }}</li>
                <li><strong>Investment:</strong> ₹{{ number_format($company->investment_amount ?? 0) }} {{ $company->investment_unit ?? 'Rupees' }}</li>
                <li><strong>Debtors:</strong> ₹{{ number_format($company->debtors_amount ?? 0) }} {{ $company->debtors_unit ?? 'Rupees' }}</li>
                <li><strong>Cash & Bank:</strong> ₹{{ number_format($company->cash_bank_amount ?? 0) }} {{ $company->cash_bank_unit ?? 'Rupees' }}</li>
                <li><strong>ROC Status:</strong> {{ $company->roc_status ?? 'N/A' }} ({{ $company->roc_year ?? 'N/A' }})</li>
                <li><strong>Income Tax Status:</strong> {{ $company->income_tax_status ?? 'N/A' }} ({{ $company->income_tax_year ?? 'N/A' }})</li>
                <li><strong>GST Status:</strong> {{ $company->gst_status ?? 'N/A' }}</li>
                <li><strong>RBI Status:</strong> {{ $company->rbi_status ?? 'N/A' }}</li>
                <li><strong>FEMA Status:</strong> {{ $company->fema_status ?? 'N/A' }}</li>
                <li><strong>GST Status:</strong> {{ $company->gst_status ?? 'N/A' }}</li>
                <li><strong>Auditor's Report:</strong> {{ $company->auditor_report ?? 'N/A' }}</li>
              </ul>
            </div>

            <button class="btn btn-link text-primary mt-auto d-flex align-items-center gap-1" type="button" data-bs-toggle="collapse" data-bs-target="#moreDetails{{ $company->id }}" aria-expanded="false" aria-controls="moreDetails{{ $company->id }}">
              <span class="toggle-text">Show More</span>
              <svg class="bi bi-chevron-down toggle-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1.646 5.646a.5.5 0 0 1 .708 0L8 11.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<script>
  document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(button => {
    button.addEventListener('click', () => {
      const target = document.querySelector(button.getAttribute('data-bs-target'));
      const toggleText = button.querySelector('.toggle-text');
      const toggleIcon = button.querySelector('.toggle-icon');

      if (target.classList.contains('show')) {
        toggleText.textContent = 'Show More';
        toggleIcon.style.transform = 'rotate(0deg)';
      } else {
        toggleText.textContent = 'Show Less';
        toggleIcon.style.transform = 'rotate(180deg)';
      }
    });
  });
</script>

<style>
  .hover-scale:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
  }

  button.btn-link {
    text-decoration: none;
    font-weight: 600;
  }

  button.btn-link:hover {
    text-decoration: underline;
  }
</style>
@endsection
