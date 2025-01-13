<?php
header('Content-type: application/json');
$data = [
    ["label" => "Value1", "data" => "Value1", "redirectPage" => "value1"],
    ["label" => "Value2", "data" => "Value2", "redirectPage" => "value2"]
];
echo json_encode(['response' => 1,
            'data' => $data])
?>

<!--<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table with Pagination</title>
    <link rel="stylesheet" href="css/output.css">

</head>
<body>

<div id="treeview" class="p-4">
    <ul class="list-none">
        <li>
            <button class="tree-toggle">📂Ay 2021-2022</button>
            <ul class="ml-4 hidden">
                <li>
                    <button class="tree-toggle">📂 First Semester</button>
                    <ul class="ml-4 hidden">
                        <li>📄 Nested Child 1</li>
                        <li>📄 Nested Child 2</li>
                    </ul>
                </li>
                <li>
                    <button class="tree-toggle">📂 Second Semester</button>
                    <ul class="ml-4 hidden">
                        <li>📄 Nested Child 1</li>
                        <li>📄 Nested Child 2</li>
                    </ul>
                </li>
                <li>
                    <button class="tree-toggle">📂 Midyear Semester</button>
                    <ul class="ml-4 hidden">
                        <li>📄 Nested Child 1</li>
                        <li>📄 Nested Child 2</li>
                    </ul>
                </li>
            </ul>
        </li>
        <li>
            <button class="tree-toggle">📂 Parent 2</button>
            <ul class="ml-4 hidden">
                <li>📄 Child 3</li>
            </ul>
        </li>
    </ul>
</div>

<script>
    document.querySelectorAll('.tree-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const subtree = button.nextElementSibling;
            if (subtree) {
                subtree.classList.toggle('hidden');
                button.textContent = subtree.classList.contains('hidden')
                    ? button.textContent.replace('📂', '📁')
                    : button.textContent.replace('📁', '📂');
            }
        });
    });

</script>

</body>
</html>
-->