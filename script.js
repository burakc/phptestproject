const submit_btn = document.getElementById("submit");
const data_table = document.getElementById("data");
const data_table_body = data_table.querySelector("tbody");
const data_header = document.querySelector("#data h2");
const no_data_element = document.querySelector(".no-data");

submit_btn.onclick = async function (e) {
  e.preventDefault();

  const userId = document.getElementById("user").value;
  if (!userId) {
    alert("Please select a user.");
    return;
  }

  data_header.textContent = "Loading..."; // Show loading state
  data_table_body.innerHTML = ""; // Clear table rows
  data_table.style.display = "none"; // Hide table initially
  no_data_element.style.display = "none"; // Hide "No transactions found" message

  try {
    const response = await fetch(`data.php?user=${userId}`);
    const results = await response.json();

    if (!results.length) {
      // Show "No transactions found" message
      no_data_element.style.display = "block";
      return;
    }

    // Dynamically update the header
    const userName = document.querySelector(
      `#user option[value="${userId}"]`
    ).textContent;
    data_header.textContent = `Transactions for ${userName}`;

    // Populate table rows
    results.forEach((row) => {
      const tr = document.createElement("tr");
      tr.innerHTML = `<td>${row.month}</td><td>${parseFloat(
        row.balance
      ).toFixed(2)}</td>`;
      data_table_body.appendChild(tr);
    });

    data_table.style.display = "block"; // Show the table
  } catch (error) {
    console.error("Error fetching data:", error);
    data_header.textContent = "An error occurred while fetching the data.";
  }
};
