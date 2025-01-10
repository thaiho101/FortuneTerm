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
        fbCostCell.innerHTML = `<input type="number" step="0.01" value="${fbCostCell.textContent.replace(",", "")}" />`;

        const otherCostCell = cells[4];
        otherCostCell.innerHTML = `<input type="number" step="0.01" value="${otherCostCell.textContent.replace(",", "")}" />`;

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
    const modal = document.createElement("div");
    modal.id = "budgetModal";
    modal.style.position = "fixed";
    modal.style.top = "50%";
    modal.style.left = "50%";
    modal.style.transform = "translate(-50%, -50%)";
    modal.style.backgroundColor = "white";
    modal.style.padding = "20px";
    modal.style.boxShadow = "0 4px 8px rgba(0, 0, 0, 0.2)";
    modal.style.borderRadius = "8px";
    modal.style.zIndex = "1000";

    modal.innerHTML = `
        <h2>Set Your Budget</h2>
        <input type="number" value="0" id="budgetInput" required placeholder="Enter your budget" style="width: 100%; margin-bottom: 10px;">
        <button id="saveBudget" onclick="applyBudget()" >Save</button>
        <button id="cancelBudget" onclick="cancelBudget()">Cancel</button>
    `;

    // Append modal to body
    document.body.appendChild(modal);
    console.log("set budget");
}
function cancelBudget() {
    // Remove the modal from the DOM
    const modal = document.getElementById("budgetModal");
    if (modal) {
        modal.remove();
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

