<?php
require('header.php');
require('mysql.inc.php');

$sql = "SELECT `id`, `name` FROM pagini WHERE deleted_at IS NULL";
$result = $conn->query($sql);

echo '<a href="page.php?action=add" style="display: block; width: 100px; margin: 20px 0; padding: 10px; background-color: #28a745; color: #fff; text-align: center; text-decoration: none; border-radius: 5px; font-family: Arial, sans-serif;">Add Page</a>';
// echo '<a href="pages/add" style="display: block; width: 100px; margin: 20px 0; padding: 10px; background-color: #28a745; color: #fff; text-align: center; text-decoration: none; border-radius: 5px; font-family: Arial, sans-serif;">Add Page</a>';
// <a href="pages/edit/' . $row['id'] . '" style="border: 1px solid #ccc; background-color: #f8f9fa; padding: 4px; border-radius: 4px; margin-right: 8px; text-decoration: none; color: #ffc107; display: inline-block;">Edit .htaccess</a>


if ($result->num_rows > 0) {
    echo '<table style="width:80%; margin: 0 auto; border-collapse: collapse; font-family: Arial, sans-serif; border: 1px solid #ccc;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="padding: 12px; border: 1px solid #ddd;">Name</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Actions</th>
            </tr>
        </thead>
        <tbody>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
            <td style="padding: 12px; border: 1px solid #ddd;"><a href="page.php?id=' . $row['id'] . '" style="text-decoration: none; color: #007bff;">' . $row['name'] . '</a></td>
            <td style="text-align: center; padding: 12px; border: 1px solid #ddd;">
                <a href="page.php?action=edit&id=' . $row['id'] . '" style="border: 1px solid #ccc; background-color: #f8f9fa; padding: 4px; border-radius: 4px; margin-right: 8px; text-decoration: none; color: #ffc107; display: inline-block;">Edit</a>
                <a href="javascript:void(0);" onclick="confirmDelete(\'page.php?action=delete&id=' . $row['id'] . '\')" style="border: 1px solid #ccc; background-color: #f8f9fa; padding: 4px; border-radius: 4px; text-decoration: none; color: #dc3545; display: inline-block;">Delete</a>
            </td>

        </tr>';
    }
    echo '</tbody></table>';
} else {
    echo '<p style="margin-top: 48px; padding: 4px; text-align: center; font-size: 16px; font-family: Arial, sans-serif; color: #666;">No pages found</p>';
}

require('footer.php');
