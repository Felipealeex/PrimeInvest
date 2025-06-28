
document.querySelectorAll(".faq-question").forEach((button) => {
    button.addEventListener("click", () => {
        const answer = button.nextElementSibling;
        const allAnswers = document.querySelectorAll(".faq-answer");

        allAnswers.forEach((item) => {
            if (item !== answer) {
                item.style.display = "none"; 
            }
        });

        answer.style.display = answer.style.display === "block" ? "none" : "block";
    });
});

const fadeInElements = document.querySelectorAll('.fade-in-element');


const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
        
            entry.target.classList.add('visible');
        } else {
        
            entry.target.classList.remove('visible');
        }
    });
}, {
    threshold: 0.5 
});

fadeInElements.forEach(element => {
    observer.observe(element);
});
