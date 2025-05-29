<?php
$results = null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['category'], $_POST['amount'])) {
    $categories = $_POST['category'];
    $amounts = $_POST['amount'];

    $expenses = [];
    $total = 0;

    for ($i = 0; $i < count($categories); $i++) {
        $cat = trim($categories[$i]);
        $amt = floatval(str_replace(',', '', $amounts[$i]));
        if ($cat && $amt > 0) {
            $expenses[] = ['category' => $cat, 'amount' => $amt];
            $total += $amt;
        }
    }

    $average = $total / 30;
    usort($expenses, fn($a, $b) => $b['amount'] <=> $a['amount']);
    $top3 = array_slice($expenses, 0, 3);

    $results = [
        'total' => $total,
        'average' => $average,
        'top3' => $top3
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expense Calculator</title>
    <style>
        input { width: 100%; }
        table { border-collapse: collapse; }
        td, th { border: 1px solid #ccc; padding: 8px; }
    </style>
</head>
<body>
<h1>Expense Calculator</h1>

<form method="POST">
    <table id="expense-table">
        <thead>
        <tr>
            <th>Category</th>
            <th>Amount ($)</th>
        </tr>
        </thead>
        <tbody>
        <?php for ($i = 0; $i < 1; $i++): ?>
            <tr>
                <td><input type="text" name="category[]" required></td>
                <td><input type="text" name="amount[]" required></td>
            </tr>
        <?php endfor; ?>
        </tbody>
    </table>

    <button type="button" onclick="addRow()">+ Add Row</button>
    <br><br>
    <button type="submit">Calculate</button>
</form>

<?php if ($results): ?>
    <h2>Results</h2>
    <p><strong>Total:</strong> $<?= number_format($results['total'], 2) ?></p>
    <p><strong>Average per day:</strong> $<?= number_format($results['average'], 2) ?></p>

    <h3>Top 3 Expenses:</h3>
    <ul>
        <?php foreach ($results['top3'] as $expense): ?>
            <li><?= htmlspecialchars($expense['category']) ?> — $<?= number_format($expense['amount'], 2) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<script>
  function addRow() {
    const table = document.getElementById("expense-table").getElementsByTagName('tbody')[0];
    const newRow = table.insertRow();

    const catCell = newRow.insertCell(0);
    const amtCell = newRow.insertCell(1);

    catCell.innerHTML = '<input type="text" name="category[]" required>';
    amtCell.innerHTML = '<input type="text" name="amount[]" required>';
  }
</script>
</body>
</html>
