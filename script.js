

function togglePasswordVisibility(inputId, toggleButton) {
    const input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
        toggleButton.textContent = "🙈"; // Đổi biểu tượng
    } else {
        input.type = "password";
        toggleButton.textContent = "👁️"; // Đổi lại biểu tượng
    }
}

function focusOnDate() {
    const inputElement = document.getElementById('date');
    inputElement.focus();
}

function focusOnHomeLink() {
    const homeLink = document.getElementById('homeLink');
    homeLink.focus();
}

async function editClick(id, event) {
    event.preventDefault();
    const row = document.getElementById(id);
    const currencyType = document.getElementById("currencyType");
    // Get the selected option's value
    const selectedValue = currencyType.value;

    if (row) {
        const cells = row.children;

        // Chuyển cột thành input field
        const dateCell = cells[1];
        const dateObject = new Date(dateCell.textContent);
	    console.log(dateCell.textContent);
        console.log(dateObject.toISOString())
        const convertedDate = dateObject.toISOString().split("T")[0];
        dateCell.innerHTML = `<input type="date" value="${convertedDate}" />`;

        const marketCell = cells[2];
        marketCell.innerHTML = `<input type="text" value="${marketCell.textContent}" />`;

        const fbCostCell = cells[3];


        const otherCostCell = cells[4];


        if (selectedValue === "VND")
        {
            fbCostCell.innerHTML = `<input type="number" step="0.01" value="${fbCostCell.textContent.replace(",", "").replace(".", "")}" />`;
            otherCostCell.innerHTML = `<input type="number" step="0.01" value="${otherCostCell.textContent.replace(",", "").replace(".", "")}" />`;
        } else {
            fbCostCell.innerHTML = `<input type="number" step="0.01" value="${fbCostCell.textContent.replace(",", "")}" />`;
            otherCostCell.innerHTML = `<input type="number" step="0.01" value="${otherCostCell.textContent.replace(",", "")}" />`;
        }

        console.log(fbCostCell.innerHTML);
        // Cập nhật nút thành Apply
        const editButton = cells[5].querySelector("button");
        editButton.setAttribute("onclick", `applyClick(${id}, event)`);
        editButton.innerHTML = `<i class="fas fa-check"></i>`;
        editButton.name = "apply";
        editButton.id = `apply_${id}`;
        editButton.classList.add("applyeditButtonStyle");
        editButton.classList.remove("editSubmitStyle");

        editButton.style.backgroundColor = "green";
        editButton.style.borderRadius = "8px";
        editButton.style.border = "white outset 1px";
        editButton.style.color = "white";
        editButton.style.cursor = "pointer";

        editButton.addEventListener("mouseover", function () {
            editButton.style.backgroundImage = "linear-gradient(white, green, white)";
        });
        
        editButton.addEventListener("mouseout", function () {
            editButton.style.backgroundImage = "";
            // editButton.style.backgroundColor = "green"; // Reset to default color
        });
    }
}

function convertDateFormat(dateString) {
    // Split the input date string
    const [year, month, day] = dateString.split("-");
    // Return the date in mm/dd/yyyy format
    return `${month}/${day}/${year}`;
}

async function applyClick(id, event) {
    event.preventDefault();
    const row = document.getElementById(id);

    if (row) {
        const cells = row.children;

        // Get values from inputs
        const date = cells[1].querySelector("input").value;
        const market = cells[2].querySelector("input").value;
        const fbCost = parseFloat(cells[3].querySelector("input").value).toFixed(2);
        const otherCost = parseFloat(cells[4].querySelector("input").value).toFixed(2);

            const response = await fetch("/api.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `action=edit&id=${id}&date=${date}&market=${market}&fbCost=${fbCost}&otherCost=${otherCost}`
            });

	window.location.reload();
    }
}




//////////////Delete Button Function//////////
const deleteButtons = document.querySelectorAll(".deleteSubmitStyle");

deleteButtons.forEach((deleteButton) => {
    deleteButton.addEventListener("click", async function (event) {
        event.preventDefault();

        const rowId = deleteButton.id.split("_")[1];
        const row = document.getElementById(rowId);

        // if(deleteButton && row){
        //     row.style.backgroundColor = 'red';
        // }

        const confirmDelete = confirm("Are you sure you want to delete this row?");
        if (!confirmDelete) return;

            const response = await fetch("/api.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `action=delete&id=${rowId}`
            });

	    window.location.reload();
    });
});

function setBudget()
{
    document.getElementById('budgetModal').style.display = 'block';
}

function cancelBudget() {
    // Remove the modal from the DOM
    const modal = document.getElementById("budgetModal");
    if (modal) {
        document.getElementById('budgetModal').style.display = 'none';
    }
    console.log("cancel budget");
}

// Send request to add/update the budget on click
async function applyBudget()
{
    // Get the current page URL
    const params = new URLSearchParams(window.location.search);

    // Extract 'year' and 'month', defaulting to the current year and month if not provided
    const currentYear = params.get('year') || new Date().getFullYear();
    const currentMonth = params.get('month') || String(new Date().getMonth() + 1).padStart(2, '0'); // Add 1 to month (0-indexed)
    const currentBudget = document.querySelector("#budgetInput").value;
    const userId = document.querySelector("#userInfo").value;

    // For debugging
    console.log(`${currentMonth} - ${currentYear} - ${currentBudget} - ${userId}`);

    // console.log("before 'try' processing in applyBudget");
    // Sending the request
        // console.log("inside 'try' processing in applyBudget");
        const response = await fetch("/api.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `action=setBudget&user_id=${userId}&month=${currentMonth}&year=${currentYear}&amount=${currentBudget}`
        });
	window.location.reload();


}

