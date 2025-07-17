@extends('layouts.dashboard')

@section('title', 'Create New Sale - PharmaPulse POS')

@section('content')
    <h1 class="text-3xl font-bold text-indigo-800 mb-6">Point of Sale (POS)</h1>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg" role="alert">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6">
        <form id="saleForm" action="{{ route('sales.store') }}" method="POST">
            @csrf

            <input type="hidden" name="client_id" value="{{ Auth::user()->client_id }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="branch_id" class="block text-gray-700 text-sm font-medium mb-2">Branch (Optional)</label>
                    <select id="branch_id" name="branch_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('branch_id') border-red-500 @enderror">
                        <option value="">Select Branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="payment_method" class="block text-gray-700 text-sm font-medium mb-2">Payment Method</label>
                    <select id="payment_method" name="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('payment_method') border-red-500 @enderror" required>
                        <option value="">Select Method</option>
                        <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Card" {{ old('payment_method') == 'Card' ? 'selected' : '' }}>Card</option>
                        <option value="UPI" {{ old('payment_method') == 'UPI' ? 'selected' : '' }}>UPI</option>
                        <option value="Other" {{ old('payment_method') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('payment_method')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="notes" class="block text-gray-700 text-sm font-medium mb-2">Notes (Optional)</label>
                    <textarea id="notes" name="notes" rows="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <hr class="my-6">

            <h2 class="text-2xl font-bold text-indigo-800 mb-4">Add Medicines</h2>
            <div class="mb-4">
                <label for="medicine_search" class="block text-gray-700 text-sm font-medium mb-2">Search Medicine (Name or Barcode)</label>
                <input type="text" id="medicine_search" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Start typing medicine name or scan barcode...">
                <div id="search_results" class="absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-1/3 max-h-60 overflow-y-auto hidden"></div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicine</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch No.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tax</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="cart_items" class="bg-white divide-y divide-gray-200">
                        <!-- Cart items will be appended here by JavaScript -->
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end">
                <div class="w-full md:w-1/2 lg:w-1/3 bg-gray-50 p-4 rounded-lg shadow-inner">
                    <div class="flex justify-between mb-2">
                        <span class="font-medium text-gray-700">Sub Total:</span>
                        <span id="display_sub_total" class="font-bold text-gray-900">INR 0.00</span>
                        <input type="hidden" name="sub_total" id="input_sub_total" value="0.00">
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="font-medium text-gray-700">Discount:</span>
                        <span id="display_discount_amount" class="font-bold text-gray-900">INR 0.00</span>
                        <input type="hidden" name="discount_amount" id="input_discount_amount" value="0.00">
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="font-medium text-gray-700">Tax:</span>
                        <span id="display_tax_amount" class="font-bold text-gray-900">INR 0.00</span>
                        <input type="hidden" name="tax_amount" id="input_tax_amount" value="0.00">
                    </div>
                    <div class="flex justify-between border-t-2 border-gray-200 pt-2 mb-4">
                        <span class="font-bold text-lg text-indigo-800">Grand Total:</span>
                        <span id="display_total_amount" class="font-extrabold text-lg text-indigo-800">INR 0.00</span>
                        <input type="hidden" name="total_amount" id="input_total_amount" value="0.00">
                    </div>

                    <div class="mb-4">
                        <label for="amount_paid" class="block text-gray-700 text-sm font-medium mb-2">Amount Paid</label>
                        <input type="number" step="0.01" id="amount_paid" name="amount_paid" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('amount_paid') border-red-500 @enderror" value="{{ old('amount_paid', 0) }}" required min="0">
                        @error('amount_paid')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-between mb-4">
                        <span class="font-medium text-gray-700">Change Due:</span>
                        <span id="display_change_due" class="font-bold text-gray-900">INR 0.00</span>
                        <input type="hidden" name="change_due" id="input_change_due" value="0.00">
                    </div>

                    <button type="submit" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 transition duration-300 font-semibold text-lg shadow-md">
                        Complete Sale
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const medicineSearchInput = document.getElementById('medicine_search');
            const searchResultsDiv = document.getElementById('search_results');
            const cartItemsTable = document.getElementById('cart_items');

            let cart = []; // Stores items in the current sale
            let selectedMedicine = null; // To hold the medicine object when adding to cart

            // Elements for totals
            const displaySubTotal = document.getElementById('display_sub_total');
            const inputSubTotal = document.getElementById('input_sub_total');
            const displayDiscountAmount = document.getElementById('display_discount_amount');
            const inputDiscountAmount = document.getElementById('input_discount_amount');
            const displayTaxAmount = document.getElementById('display_tax_amount');
            const inputTaxAmount = document.getElementById('input_tax_amount');
            const displayTotalAmount = document.getElementById('display_total_amount');
            const inputTotalAmount = document.getElementById('input_total_amount');
            const amountPaidInput = document.getElementById('amount_paid');
            const displayChangeDue = document.getElementById('display_change_due');
            const inputChangeDue = document.getElementById('input_change_due');

            // Function to update totals
            function updateTotals() {
                let subTotal = 0;
                let totalDiscount = 0;
                let totalTax = 0;

                cart.forEach(item => {
                    subTotal += item.quantity * item.unit_price;
                    totalDiscount += item.discount_amount * item.quantity; // Assuming discount is per unit
                    totalTax += item.tax_amount * item.quantity; // Assuming tax is per unit
                });

                let grandTotal = subTotal - totalDiscount + totalTax;
                let changeDue = amountPaidInput.value - grandTotal;

                displaySubTotal.textContent = `INR ${subTotal.toFixed(2)}`;
                inputSubTotal.value = subTotal.toFixed(2);
                displayDiscountAmount.textContent = `INR ${totalDiscount.toFixed(2)}`;
                inputDiscountAmount.value = totalDiscount.toFixed(2);
                displayTaxAmount.textContent = `INR ${totalTax.toFixed(2)}`;
                inputTaxAmount.value = totalTax.toFixed(2);
                displayTotalAmount.textContent = `INR ${grandTotal.toFixed(2)}`;
                inputTotalAmount.value = grandTotal.toFixed(2);
                displayChangeDue.textContent = `INR ${changeDue.toFixed(2)}`;
                inputChangeDue.value = changeDue.toFixed(2);
            }

            // Event listener for amount paid input
            amountPaidInput.addEventListener('input', updateTotals);

            // Fetch medicines on search input
            let searchTimeout;
            medicineSearchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();
                if (query.length < 2) {
                    searchResultsDiv.innerHTML = '';
                    searchResultsDiv.classList.add('hidden');
                    return;
                }

                searchTimeout = setTimeout(function() {
                    fetch(`{{ route('sales.searchMedicines') }}?query=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            searchResultsDiv.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(medicine => {
                                    const div = document.createElement('div');
                                    div.classList.add('p-2', 'cursor-pointer', 'hover:bg-indigo-100', 'border-b', 'border-gray-100');
                                    div.textContent = `${medicine.name} (${medicine.brand || 'N/A'}) - Stock: ${medicine.total_stock}`;
                                    div.dataset.medicine = JSON.stringify(medicine); // Store full medicine data
                                    div.addEventListener('click', function() {
                                        selectedMedicine = JSON.parse(this.dataset.medicine);
                                        addMedicineToCart(selectedMedicine);
                                        medicineSearchInput.value = '';
                                        searchResultsDiv.classList.add('hidden');
                                    });
                                    searchResultsDiv.appendChild(div);
                                });
                                searchResultsDiv.classList.remove('hidden');
                            } else {
                                searchResultsDiv.innerHTML = '<div class="p-2 text-gray-500">No results found.</div>';
                                searchResultsDiv.classList.remove('hidden');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching medicines:', error);
                            searchResultsDiv.innerHTML = '<div class="p-2 text-red-500">Error fetching results.</div>';
                            searchResultsDiv.classList.remove('hidden');
                        });
                }, 300); // Debounce search
            });

            // Hide search results when clicking outside
            document.addEventListener('click', function(event) {
                if (!medicineSearchInput.contains(event.target) && !searchResultsDiv.contains(event.target)) {
                    searchResultsDiv.classList.add('hidden');
                }
            });

            // Function to add medicine to cart
            function addMedicineToCart(medicine) {
                // If medicine has batches, prompt user to select batch
                if (medicine.batches && medicine.batches.length > 0) {
                    let batchOptions = medicine.batches.map(b => `<option value="${b.id}" data-quantity="${b.current_quantity}" data-price="${b.cost_price}">${b.batch_number} (Exp: ${b.expiry_date}) - Stock: ${b.current_quantity}</option>`).join('');
                    const batchSelectHtml = `
                        <select class="batch-select w-full p-1 border rounded text-sm">
                            <option value="">Select Batch</option>
                            ${batchOptions}
                        </select>
                    `;

                    const quantityInputHtml = `
                        <input type="number" class="quantity-input w-full p-1 border rounded text-sm" value="1" min="1" max="${medicine.batches[0].current_quantity}">
                    `;

                    const priceInputHtml = `
                        <input type="number" step="0.01" class="price-input w-full p-1 border rounded text-sm" value="${medicine.batches[0].cost_price}" min="0.01">
                    `;

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 text-sm text-gray-900">${medicine.name} (${medicine.strength} ${medicine.unit})</td>
                        <td class="px-6 py-4 text-sm text-gray-600">${batchSelectHtml}</td>
                        <td class="px-6 py-4 text-sm text-gray-600"><span class="expiry-display">N/A</span></td>
                        <td class="px-6 py-4 text-sm text-gray-600">${priceInputHtml}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">${quantityInputHtml}</td>
                        <td class="px-6 py-4 text-sm text-gray-600"><input type="number" step="0.01" class="discount-input w-full p-1 border rounded text-sm" value="0"></td>
                        <td class="px-6 py-4 text-sm text-gray-600"><input type="number" step="0.01" class="tax-input w-full p-1 border rounded text-sm" value="0"></td>
                        <td class="px-6 py-4 text-sm text-gray-900 total-item-price">INR 0.00</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <button type="button" class="remove-item text-red-600 hover:text-red-900">Remove</button>
                        </td>
                        <input type="hidden" class="medicine-id-input" value="${medicine.id}">
                        <input type="hidden" class="batch-id-input" value="">
                        <input type="hidden" class="item-sub-total-input" value="0">
                        <input type="hidden" class="item-discount-amount-input" value="0">
                        <input type="hidden" class="item-tax-amount-input" value="0">
                        <input type="hidden" class="item-total-price-input" value="0">
                    `;
                    cartItemsTable.appendChild(row);

                    const batchSelect = row.querySelector('.batch-select');
                    const quantityInput = row.querySelector('.quantity-input');
                    const priceInput = row.querySelector('.price-input');
                    const discountInput = row.querySelector('.discount-input');
                    const taxInput = row.querySelector('.tax-input');
                    const expiryDisplay = row.querySelector('.expiry-display');
                    const totalItemPriceDisplay = row.querySelector('.total-item-price');
                    const medicineIdInput = row.querySelector('.medicine-id-input');
                    const batchIdInput = row.querySelector('.batch-id-input');
                    const itemSubTotalInput = row.querySelector('.item-sub-total-input');
                    const itemDiscountAmountInput = row.querySelector('.item-discount-amount-input');
                    const itemTaxAmountInput = row.querySelector('.item-tax-amount-input');
                    const itemTotalPriceInput = row.querySelector('.item-total-price-input');

                    // Function to update item row calculations
                    function updateItemRow() {
                        const qty = parseInt(quantityInput.value) || 0;
                        const price = parseFloat(priceInput.value) || 0;
                        const discount = parseFloat(discountInput.value) || 0;
                        const tax = parseFloat(taxInput.value) || 0;

                        const itemSubTotal = qty * price;
                        const itemTotal = itemSubTotal - discount + tax;

                        totalItemPriceDisplay.textContent = `INR ${itemTotal.toFixed(2)}`;
                        itemSubTotalInput.value = itemSubTotal.toFixed(2);
                        itemDiscountAmountInput.value = discount.toFixed(2);
                        itemTaxAmountInput.value = tax.toFixed(2);
                        itemTotalPriceInput.value = itemTotal.toFixed(2);

                        // Update cart array and overall totals
                        const index = Array.from(cartItemsTable.children).indexOf(row);
                        if (index !== -1) {
                            cart[index] = {
                                medicine_id: medicineIdInput.value,
                                batch_id: batchIdInput.value,
                                quantity: qty,
                                unit_price: price,
                                sub_total: itemSubTotal,
                                discount_amount: discount,
                                tax_amount: tax,
                                total_price: itemTotal
                            };
                        }
                        updateTotals();
                    }

                    batchSelect.addEventListener('change', function() {
                        const selectedOption = batchSelect.options[batchSelect.selectedIndex];
                        const selectedBatchId = selectedOption.value;
                        const selectedBatchQty = parseInt(selectedOption.dataset.quantity);
                        const selectedBatchPrice = parseFloat(selectedOption.dataset.price);

                        batchIdInput.value = selectedBatchId;
                        quantityInput.max = selectedBatchQty;
                        priceInput.value = selectedBatchPrice.toFixed(2);
                        expiryDisplay.textContent = selectedOption.textContent.match(/Exp: (\d{4}-\d{2}-\d{2})/)?.[1] || 'N/A';
                        updateItemRow();
                    });

                    quantityInput.addEventListener('input', updateItemRow);
                    priceInput.addEventListener('input', updateItemRow);
                    discountInput.addEventListener('input', updateItemRow);
                    taxInput.addEventListener('input', updateItemRow);

                    // Remove item from cart
                    row.querySelector('.remove-item').addEventListener('click', function() {
                        const index = Array.from(cartItemsTable.children).indexOf(row);
                        if (index !== -1) {
                            cart.splice(index, 1);
                            row.remove();
                            updateTotals();
                        }
                    });

                    // Trigger initial update for default values
                    updateItemRow();
                    if (medicine.batches.length > 0) {
                        batchSelect.value = medicine.batches[0].id; // Select first batch by default
                        batchSelect.dispatchEvent(new Event('change')); // Trigger change to populate fields
                    }
                } else {
                    // Handle medicine without specific batches (e.g., services, or simple stock)
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 text-sm text-gray-900">${medicine.name} (${medicine.strength} ${medicine.unit})</td>
                        <td class="px-6 py-4 text-sm text-gray-600">N/A</td>
                        <td class="px-6 py-4 text-sm text-gray-600">N/A</td>
                        <td class="px-6 py-4 text-sm text-gray-600"><input type="number" step="0.01" class="price-input w-full p-1 border rounded text-sm" value="0.00" min="0.01"></td>
                        <td class="px-6 py-4 text-sm text-gray-600"><input type="number" class="quantity-input w-full p-1 border rounded text-sm" value="1" min="1"></td>
                        <td class="px-6 py-4 text-sm text-gray-600"><input type="number" step="0.01" class="discount-input w-full p-1 border rounded text-sm" value="0"></td>
                        <td class="px-6 py-4 text-sm text-gray-600"><input type="number" step="0.01" class="tax-input w-full p-1 border rounded text-sm" value="0"></td>
                        <td class="px-6 py-4 text-sm text-gray-900 total-item-price">INR 0.00</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <button type="button" class="remove-item text-red-600 hover:text-red-900">Remove</button>
                        </td>
                        <input type="hidden" class="medicine-id-input" value="${medicine.id}">
                        <input type="hidden" class="batch-id-input" value="">
                        <input type="hidden" class="item-sub-total-input" value="0">
                        <input type="hidden" class="item-discount-amount-input" value="0">
                        <input type="hidden" class="item-tax-amount-input" value="0">
                        <input type="hidden" class="item-total-price-input" value="0">
                    `;
                    cartItemsTable.appendChild(row);

                    const quantityInput = row.querySelector('.quantity-input');
                    const priceInput = row.querySelector('.price-input');
                    const discountInput = row.querySelector('.discount-input');
                    const taxInput = row.querySelector('.tax-input');
                    const totalItemPriceDisplay = row.querySelector('.total-item-price');
                    const medicineIdInput = row.querySelector('.medicine-id-input');
                    const batchIdInput = row.querySelector('.batch-id-input');
                    const itemSubTotalInput = row.querySelector('.item-sub-total-input');
                    const itemDiscountAmountInput = row.querySelector('.item-discount-amount-input');
                    const itemTaxAmountInput = row.querySelector('.item-tax-amount-input');
                    const itemTotalPriceInput = row.querySelector('.item-total-price-input');

                     function updateItemRow() {
                        const qty = parseInt(quantityInput.value) || 0;
                        const price = parseFloat(priceInput.value) || 0;
                        const discount = parseFloat(discountInput.value) || 0;
                        const tax = parseFloat(taxInput.value) || 0;

                        const itemSubTotal = qty * price;
                        const itemTotal = itemSubTotal - discount + tax;

                        totalItemPriceDisplay.textContent = `INR ${itemTotal.toFixed(2)}`;
                        itemSubTotalInput.value = itemSubTotal.toFixed(2);
                        itemDiscountAmountInput.value = discount.toFixed(2);
                        itemTaxAmountInput.value = tax.toFixed(2);
                        itemTotalPriceInput.value = itemTotal.toFixed(2);

                        const index = Array.from(cartItemsTable.children).indexOf(row);
                        if (index !== -1) {
                            cart[index] = {
                                medicine_id: medicineIdInput.value,
                                batch_id: batchIdInput.value,
                                quantity: qty,
                                unit_price: price,
                                sub_total: itemSubTotal,
                                discount_amount: discount,
                                tax_amount: tax,
                                total_price: itemTotal
                            };
                        }
                        updateTotals();
                    }

                    quantityInput.addEventListener('input', updateItemRow);
                    priceInput.addEventListener('input', updateItemRow);
                    discountInput.addEventListener('input', updateItemRow);
                    taxInput.addEventListener('input', updateItemRow);

                    row.querySelector('.remove-item').addEventListener('click', function() {
                        const index = Array.from(cartItemsTable.children).indexOf(row);
                        if (index !== -1) {
                            cart.splice(index, 1);
                            row.remove();
                            updateTotals();
                        }
                    });
                    updateItemRow();
                }
                updateTotals(); // Recalculate totals after adding item
            }

            // Before submitting the form, populate the hidden input for items
            document.getElementById('saleForm').addEventListener('submit', function(event) {
                // Remove any existing hidden item inputs to prevent duplicates on re-submission
                document.querySelectorAll('input[name^="items["]').forEach(input => input.remove());

                cart.forEach((item, index) => {
                    for (const key in item) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `items[${index}][${key}]`;
                        input.value = item[key];
                        this.appendChild(input);
                    }
                });
            });

            // Initial total calculation
            updateTotals();
        });
    </script>
@endsection