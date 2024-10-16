<form action="{{ route('payment.initiate') }}" method="POST">
    @csrf
    <label for="amount">Amount</label>
    <input type="number" name="amount" id="amount" required>
    
    <label for="email">Email</label>
    <input type="email" name="email" id="email" required>
    
    <label for="phone">Phone</label>
    <input type="text" name="phone" id="phone" required>

    <button type="submit">Pay Now</button>
</form>
