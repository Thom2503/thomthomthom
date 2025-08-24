window.onload = () => {
	const helpDialog = document.querySelector("dialog");
	const showHelpButton = document.querySelector("button[id=help-button]");
	const closeHelpButton = document.querySelector("dialog button");

	showHelpButton.addEventListener("click", () => {
		helpDialog.show();
	});
	closeHelpButton.addEventListener("click", () => {
		helpDialog.close();
	});
};
