const { PDFDocument } = require('pdf-lib');
const fs = require('fs');

async function createPdf() {
    const pdfDoc = await PDFDocument.create();
    
    // Select the best screenshots for the LinkedIn post
    const images = [
        'admin-02-dashboard.png',
        'admin-03-students.png',
        'admin-04-fees.png',
        'admin-05-reports.png',
        'student-02-portal.png'
    ];
    
    for (const img of images) {
        const imageBytes = fs.readFileSync(`screenshots/desktop/${img}`);
        const image = await pdfDoc.embedPng(imageBytes);
        
        // Scale the image appropriately for a presentation
        const { width, height } = image.scale(1);
        
        // Add a blank page with the exact dimensions of the image
        const page = pdfDoc.addPage([width, height]);
        page.drawImage(image, {
            x: 0,
            y: 0,
            width: width,
            height: height,
        });
    }
    
    const pdfBytes = await pdfDoc.save();
    fs.writeFileSync('College_Fee_System_Showcase.pdf', pdfBytes);
    console.log('Successfully created College_Fee_System_Showcase.pdf for LinkedIn!');
}

createPdf().catch(err => console.error(err));
