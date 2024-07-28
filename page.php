<?php
require('header.php');
require('mysql.inc.php');


$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'add') {

    // Checking the request method, to avoid handling both fetching and updating data simultaneously
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $continut = $_POST['continut'];
        $data = $_POST['data'];

        $sql = "INSERT INTO pagini (name, continut, data) VALUES ('$name', '$continut', '$data')";

        // If the query is successful the user is redirected to index.php
        if ($conn->query($sql) === TRUE) {
            header('Location: index.php');
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // Displaying the form for adding a new page
?>
        <h1 style="font-family: Arial, sans-serif; text-align: center;">Add Page</h1>
        <form method="post" action="page.php?action=add" style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
            <div style="margin-bottom: 12px;">
                <label for="name" style="font-family: Arial, sans-serif;">Name:</label><br>
                <input type="text" id="name" name="name" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 12px;">
                <label for="continut" style="font-family: Arial, sans-serif;">Continut:</label><br>
                <input type="text" id="continut" name="continut" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 12px;">
                <label for="data" style="font-family: Arial, sans-serif;">Data:</label><br>
                <input type="datetime-local" id="data" name="data" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="text-align: center;">
                <input type="submit" value="Add Page" style="padding: 10px 20px; background-color: #28a745; color: #fff; border: none; border-radius: 5px; font-family: Arial, sans-serif;">
            </div>
        </form>
    <?php
    }
} elseif ($action == 'edit' && isset($_GET['id']) && $_GET['id'] > 0) {
    $id = $_GET['id'];

    // Checking the request method to avoid handling both fetching and updating data simultaneously
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $continut = $_POST['continut'];
        $data = $_POST['data'];

        $sql = "UPDATE pagini SET name='$name', continut='$continut', data='$data' WHERE id='$id' AND deleted_at IS NULL";

        // If the query is successful the user is redirected to index.php
        if ($conn->query($sql) === TRUE) {
            header('Location: index.php');
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Fetching existing page data for the form
    } else {
        $sql = "SELECT `id`, `name`, `continut`, `data` FROM pagini WHERE id='$id'";
        $result = $conn->query($sql);

        // Checking if the query returned any results
        if ($result->num_rows > 0) {

            // Fetching the data for the page if the query returned results.
            $row = $result->fetch_assoc();
        } else {
            echo 'Page not found';
            exit();
        }
        // Displaying the form for editing the page
    ?>
        <h1 style="font-family: Arial, sans-serif; text-align: center;">Edit Page</h1>
        <form method="post" action="page.php?action=edit&id=<?php echo $id; ?>" style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
            <div style="margin-bottom: 12px;">
                <label for="name" style="font-family: Arial, sans-serif;">Name:</label><br>
                <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 12px;">
                <label for="continut" style="font-family: Arial, sans-serif;">Continut:</label><br>
                <input type="text" id="continut" name="continut" value="<?php echo $row['continut']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 12px;">
                <label for="data" style="font-family: Arial, sans-serif;">Data:</label><br>
                <input type="datetime-local" id="data" name="data" value="<?php echo $row['data']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="text-align: center;">
                <input type="submit" value="Save Changes" style="padding: 10px 20px; background-color: #007bff; color: #fff; border: none; border-radius: 5px; font-family: Arial, sans-serif;">
            </div>
        </form>
<?php
    }
} elseif ($action == 'delete' && isset($_GET['id']) && $_GET['id'] > 0) {
    $id = $_GET['id'];

    //$sql = "DELETE FROM pagini WHERE id='$id'";
    //Soft delete
    $sql = "UPDATE pagini SET deleted_at=NOW() WHERE id='$id'";

    // If the query is successful the user is redirected to index.php
    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif (isset($_GET['id']) && $_GET['id'] > 0) {
    $id = $_GET['id'];

    // Fetching the page data based on the provided ID
    $sql = "SELECT `id`, `name`, `continut`, `data` FROM pagini WHERE id='$id' AND deleted_at IS NULL";
    $result = $conn->query($sql);

    // Checking if the query returned any results
    if ($result->num_rows > 0) {

        // Fetching the data for the page if the query returned results.
        $row = $result->fetch_assoc();

        // Displaying the page data in an HTML table
        echo '<table style="width:100%; border: 1px solid #ddd; border-collapse: collapse; margin: 20px 0; font-family: Arial, sans-serif;">
            <thead>
                <tr style="background-color: #f8f9fa;">
                    <th style="text-align: center; padding: 12px; border: 1px solid #ddd;">ID</th>
                    <th style="text-align: center; padding: 12px; border: 1px solid #ddd;">Name</th>
                    <th style="text-align: center; padding: 12px; border: 1px solid #ddd;">Continut</th>
                    <th style="text-align: center; padding: 12px; border: 1px solid #ddd;">Data</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td style="text-align: center; padding: 12px; border: 1px solid #ddd;">' . $row['id'] . '</td>
                <td style="text-align: center; padding: 12px; border: 1px solid #ddd;">' . $row['name'] . '</td>
                <td style="text-align: center; padding: 12px; border: 1px solid #ddd;">' . $row['continut'] . '</td>
                <td style="text-align: center; padding: 12px; border: 1px solid #ddd;">' . $row['data'] . '</td>
            </tr>
            </tbody>
        </table>';
    } else {
        echo '<p style="margin-top: 48px; padding: 4px; text-align: center; font-size: 16px;">Page not found</p>';
    }
} else {
    echo '<p style="margin-top: 48px; padding: 4px; text-align: center; font-size: 16px;">Invalid action</p>';
}

require('footer.php');
?>