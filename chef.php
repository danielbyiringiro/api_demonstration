<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Daniel Byiringiro and Julia Mc-Addy -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer's Produce</title>
    <style>
        /* Add some basic styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        select, table {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h1>Farmer's Produce</h1>
    <div id="error-message"></div>
    
    <div id="api-explanation">
        <h2>How the API Works</h2>
        <p>The frontend interacts with a backend API to retrieve data about various produce items. This interaction involves the following steps:</p>
        <ol>
            <li>The user selects a produce type from the dropdown menu.</li>
            <li>Upon selection, the client-side JavaScript code sends an HTTP request to the server-side API endpoint corresponding to the selected produce type.</li>
            <li>The server-side PHP script processes the request, retrieves the requested data, and sends back a response.</li>
            <li>The client-side JavaScript code receives the response and dynamically updates the webpage to display the produce data.</li>
        </ol>
        <p>This client-server interaction allows for dynamic and interactive display of produce data on the webpage.</p>
    </div>

    <label for="produce-type">Select Produce Type:</label>
    <select id="produce-type" onchange="fetchAndDisplayProduce()">
        <option value="">-- Select Type --</option>
        <option value="all">All Produce</option>
        <option value="vegetables">Vegetables</option>
        <option value="fruits">Fruits</option>
        <option value="grains">Grains</option>
        <option value="legumes">Legumes</option>
        <option value="herbs">Herbs</option>
        <option value="spices">Spices</option>
    </select>

    <div id="produce-tables"></div>

    <script>
        // Function to fetch produce data from the API
        function fetchProduce(action, type = '') {
            let url = `farmer.php?action=${action}`;
            if (type !== '') {
                url += `&type=${type}`;
            }
            return fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                    return { error: 'There was a problem with the fetch operation.' };
                });
        }

        // Function to display produce data in a table
        function displayProduceTable(produce) {
            const table = document.createElement('table');
            const headerRow = table.insertRow();
            const nameHeader = headerRow.insertCell();
            const quantityHeader = headerRow.insertCell();
            nameHeader.textContent = 'Name';
            quantityHeader.textContent = 'Quantity';
            produce.forEach(item => {
                const row = table.insertRow();
                const nameCell = row.insertCell();
                const quantityCell = row.insertCell();
                nameCell.textContent = item.name;
                quantityCell.textContent = item.quantity;
            });
            return table;
        }

        // Function to display produce data for a specific type
        function displayProduceByType(type, produce) {
            const produceTables = document.getElementById('produce-tables');
            produceTables.innerHTML = '';
            const produceTable = displayProduceTable(produce);
            produceTables.appendChild(document.createElement('hr')); // Add a horizontal line for separation
            produceTables.appendChild(document.createElement('h2')).textContent = type.charAt(0).toUpperCase() + type.slice(1); // Add category title
            produceTables.appendChild(produceTable);
        }

        // Function to fetch and display produce based on selected type
        function fetchAndDisplayProduce() {
            const produceType = document.getElementById('produce-type').value;
            if (produceType !== '') {
                if (produceType === 'all') {
                    fetchProduce('getAllProduce')
                        .then(produce => {
                            if (produce.error) {
                                document.getElementById('error-message').textContent = produce.error;
                            } else {
                                document.getElementById('error-message').textContent = '';
                                displayAllProduce(produce);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                } else {
                    fetchProduce('getProduce', produceType)
                        .then(produce => {
                            if (produce.error) {
                                document.getElementById('error-message').textContent = produce.error;
                            } else {
                                document.getElementById('error-message').textContent = '';
                                displayProduceByType(produceType, produce);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            } else {
                document.getElementById('produce-tables').innerHTML = '';
            }
        }

        // Function to display all produce data in tables
        function displayAllProduce(produce) {
            const produceTables = document.getElementById('produce-tables');
            produceTables.innerHTML = '';
            for (const category in produce) {
                if (produce.hasOwnProperty(category)) {
                    const produceTable = displayProduceTable(produce[category]);
                    produceTables.appendChild(document.createElement('hr')); // Add a horizontal line for separation
                    produceTables.appendChild(document.createElement('h2')).textContent = category.charAt(0).toUpperCase() + category.slice(1); // Add category title
                    produceTables.appendChild(produceTable);
                }
            }
        }
    </script>
</body>
</html>
