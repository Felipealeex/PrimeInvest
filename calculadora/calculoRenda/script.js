document.getElementById("calculate-button").addEventListener("click", function () {
    const progressBar = document.getElementById("progress-bar");
    const progressContainer = document.getElementById("progress-container");
    const resultSection = document.getElementById("result-section");

    progressBar.style.width = "0%";
    progressContainer.style.display = "block";

    let progress = 0;
    const interval = setInterval(() => {
        progress += 5;
        progressBar.style.width = progress + "%";

        if (progress >= 100) {
            clearInterval(interval);
            progressContainer.style.display = "none";
            resultSection.style.display = "block";
        }
    }, 100);
});
