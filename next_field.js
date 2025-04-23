let currentStep = 1;

function goNextStep() {
  const part1Fields = [
    "empID", "firstName", "lastName", "middleName", "gender",
    "civilStatus", "age", "email", "contactNumber"
  ];
  const part2Fields = [
    "dateHired", "jobPosition", "department", "tinNumber",
    "sssNumber", "philHealth", "pagibig", "ratePerHour",
    "paymentMode", "backupPayment"
  ];
  const part3Fields = [
    "emergencyNumber", "emergencyName", "relation"
  ];

  let valid = true;
  if (currentStep === 1) {
    valid = validateFields(part1Fields);
    if (valid) {
      document.querySelector(".employee-form").style.display = "none";
      document.getElementById("part2").classList.remove("hidden");
      currentStep++;
    }
  } else if (currentStep === 2) {
    valid = validateFields(part2Fields);
    if (valid) {
      document.getElementById("part2").classList.add("hidden");
      document.getElementById("part3").classList.remove("hidden");

      // 👈 ADDED: Hide instructions image on step 3
      const instructionImageContainer = document.querySelector(".instruction-image-container");
      if (instructionImageContainer) {
        instructionImageContainer.style.display = "none";
      }

      currentStep++;
    }
  }
}


function goBackStep() {
  if (currentStep === 2) {
    document.querySelector(".employee-form").style.display = "block";
    document.getElementById("part2").classList.add("hidden");
    currentStep--;
  } else if (currentStep === 3) {
    document.getElementById("part2").classList.remove("hidden");
    document.getElementById("part3").classList.add("hidden");
    currentStep--;
  }
}

function validateFields(fieldIds) {
  let isValid = true;
  fieldIds.forEach(id => {
    const field = document.getElementById(id);
    if (!field.value.trim()) {
      field.style.border = "2px solid red";
      isValid = false;
    } else {
      field.style.border = "";
    }
  });

  if (!isValid) {
    showRequiredModal(); // show our custom modal
  }

  return isValid;
}

// Modal handler for missing fields
function showRequiredModal() {
  const modal = document.getElementById("requiredFieldsModal");
  modal.style.display = "flex";
}

function closeRequiredModal() {
  const modal = document.getElementById("requiredFieldsModal");
  modal.style.display = "none";
}

window.goNextStep = goNextStep;
window.goBackStep = goBackStep;
window.closeRequiredModal = closeRequiredModal;

function submitPart3() {
  const part3Fields = [
    "emergencyNumber", "emergencyName", "relation"
  ];

  const valid = validateFields(part3Fields);

  if (valid) {
    showSubmitModal();
  } else {
    showRequiredModal();
  }
}

// Dummy function for showSubmitModal, replace this with your own logic
function showSubmitModal() {
  const modal = document.getElementById("submitModal");
  modal.style.display = "flex";
}

function closeSubmitModal() {
  const modal = document.getElementById("submitModal");
  modal.style.display = "none";
}


window.submitPart3 = submitPart3;
window.showSubmitModal = showSubmitModal;

