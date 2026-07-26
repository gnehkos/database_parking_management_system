<x-layout title="Fee Rates">
    <div class="page-header"><div class="page-title">Fee Rates</div><div class="page-sub">Flat rate pricing per vehicle type</div></div>

    <div class="row g-3 mb-4">
        @foreach (['car','motorcycle','bike','tricycle'] as $type)
            @php $rate=$rates[$type]??null; $clrs=['car'=>'var(--blue)','motorcycle'=>'var(--green)','bike'=>'var(--orange)','tricycle'=>'var(--purple)'];$c=$clrs[$type]; @endphp
            <div class="col-md-6">
                <div class="card-ios overflow-hidden">
                    <div style="height:3px;background:{{ $c }}"></div>
                    <div class="card-ios-p">
                        <div style="font-size:14px;font-weight:700;text-transform:uppercase;color:{{ $c }};margin-bottom:4px">{{ ucfirst($type) }}</div>
                        <div style="font-size:12px;color:var(--gray);margin-bottom:16px">Flat rate pricing</div>
                        @if ($rate)
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <div style="font-size:10px;color:var(--gray);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px">Under {{ $rate->threshold_hours }}h</div>
                                    <div style="font-size:26px;font-weight:800">${{ number_format($rate->short_stay_fee,2) }}</div>
                                </div>
                                <div class="col-6">
                                    <div style="font-size:10px;color:var(--gray);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px">{{ $rate->threshold_hours }}h or more</div>
                                    <div style="font-size:26px;font-weight:800">${{ number_format($rate->long_stay_fee,2) }}</div>
                                </div>
                            </div>
                            <div style="font-size:12px;color:var(--gray);margin-bottom:16px">≈ {{ number_format($rate->short_stay_fee*4000) }} / {{ number_format($rate->long_stay_fee*4000) }} KHR</div>

                            <button class="ios-btn btn-sm-ios" style="background:{{ $c }}15;color:{{ $c }}" onclick="this.style.display='none';document.getElementById('form-{{ $type }}').style.display='block'">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </button>

                            <form method="POST" action="{{ route('fees.update',$rate->rate_id) }}" id="form-{{ $type }}" style="display:none;margin-top:12px">
                                @csrf @method('PATCH')
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label style="font-size:12px;color:var(--gray);display:block;margin-bottom:4px">Under {{ $rate->threshold_hours }}h (USD)</label>
                                        <input type="number" step="0.01" name="short_stay_fee" class="ios-input" style="font-size:14px" value="{{ $rate->short_stay_fee }}">
                                    </div>
                                    <div class="col-6">
                                        <label style="font-size:12px;color:var(--gray);display:block;margin-bottom:4px">{{ $rate->threshold_hours }}h+ (USD)</label>
                                        <input type="number" step="0.01" name="long_stay_fee" class="ios-input" style="font-size:14px" value="{{ $rate->long_stay_fee }}">
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="ios-btn btn-primary-ios btn-sm-ios">Save</button>
                                    <button type="button" class="ios-btn btn-ghost btn-sm-ios" onclick="this.closest('form').style.display='none';this.closest('.card-ios').querySelector('[onclick*=form]').style.display=''">Cancel</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card-ios card-ios-p">
        <div style="font-size:13px;color:var(--gray)">
            <i class="bi bi-info-circle me-1"></i>
            Rates are fixed per session: <strong>short stay</strong> for under 5 hours, <strong>long stay</strong> for 5 hours or more. Exchange rate: <strong>1 USD = 4,000 KHR</strong>.
        </div>
    </div>
</x-layout>
