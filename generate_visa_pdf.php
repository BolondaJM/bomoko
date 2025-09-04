<?php
// Simple PDF generation for visa application summary
// This creates an HTML file that can be printed as PDF

function generateVisaPDF($data) {
    $html = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Visa Application Summary - DRC Embassy</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
                background-color: #f5f5f5;
            }
            .pdf-container {
                max-width: 800px;
                margin: 0 auto;
                background: white;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            .header {
                text-align: center;
                border-bottom: 3px solid #003366;
                padding-bottom: 20px;
                margin-bottom: 30px;
            }
            .header h1 {
                color: #003366;
                margin: 0;
                font-size: 28px;
            }
            .header p {
                color: #666;
                margin: 5px 0 0 0;
                font-size: 14px;
            }
            .section {
                margin-bottom: 25px;
            }
            .section h2 {
                color: #003366;
                border-bottom: 2px solid #e0e6ef;
                padding-bottom: 8px;
                margin-bottom: 15px;
                font-size: 20px;
            }
            .info-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 15px;
            }
            .info-item {
                margin-bottom: 12px;
            }
            .info-label {
                font-weight: bold;
                color: #333;
                margin-bottom: 5px;
                font-size: 14px;
            }
            .info-value {
                color: #666;
                padding: 8px 12px;
                background-color: #f8f9fa;
                border-radius: 4px;
                border-left: 4px solid #003366;
                font-size: 14px;
            }
            .full-width {
                grid-column: 1 / -1;
            }
            .footer {
                margin-top: 40px;
                padding-top: 20px;
                border-top: 1px solid #e0e6ef;
                text-align: center;
                color: #666;
                font-size: 12px;
            }
            .application-id {
                background: #003366;
                color: white;
                padding: 10px 15px;
                border-radius: 5px;
                text-align: center;
                margin-bottom: 20px;
                font-weight: bold;
            }
            .important-note {
                background: #fff3cd;
                border: 1px solid #ffeaa7;
                border-radius: 5px;
                padding: 15px;
                margin: 20px 0;
                color: #856404;
            }
            .important-note h3 {
                margin: 0 0 10px 0;
                color: #856404;
            }
            @media print {
                body {
                    background-color: white;
                }
                .pdf-container {
                    box-shadow: none;
                    border: 1px solid #ccc;
                }
            }
        </style>
    </head>
    <body>
        <div class="pdf-container">
            <div class="header">
                <h1>DRC Embassy Nairobi</h1>
                <p>Democratic Republic of Congo Embassy in Kenya</p>
                <p>Visa Application Summary</p>
            </div>
            
            <div class="application-id">
                Application ID: ' . strtoupper(substr(md5($data["email"] . $data["passport_number"]), 0, 8)) . '
            </div>
            
            <div class="important-note">
                <h3>Important Information:</h3>
                <p>• Please keep this summary for your records</p>
                <p>• Application processing typically takes 5-7 working days</p>
                <p>• You will be notified via email about the status of your application</p>
                <p>• Please ensure all required documents are uploaded</p>
            </div>
            
            <div class="section">
                <h2>Personal Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">' . htmlspecialchars($data["full_name"]) . '</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email Address</div>
                        <div class="info-value">' . htmlspecialchars($data["email"]) . '</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Gender</div>
                        <div class="info-value">' . htmlspecialchars(ucfirst($data["gender"])) . '</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Date of Birth</div>
                        <div class="info-value">' . htmlspecialchars($data["dob"]) . '</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Country of Origin</div>
                        <div class="info-value">' . htmlspecialchars($data["country"]) . '</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Passport Number</div>
                        <div class="info-value">' . htmlspecialchars($data["passport_number"]) . '</div>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <h2>Visa Details</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Visa Type</div>
                        <div class="info-value">' . htmlspecialchars(ucwords(str_replace("_", " ", $data["visa_type"]))) . '</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Duration of Stay</div>
                        <div class="info-value">' . htmlspecialchars($data["duration"]) . '</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Intended Travel Date</div>
                        <div class="info-value">' . htmlspecialchars($data["travel_date"]) . '</div>
                    </div>
                </div>
                <div class="info-item full-width">
                    <div class="info-label">Reason for Travel</div>
                    <div class="info-value">' . htmlspecialchars($data["reason"]) . '</div>
                </div>
            </div>
            
            <div class="section">
                <h2>Application Status</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Application Date</div>
                        <div class="info-value">' . date("F j, Y") . '</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value" style="color: #28a745; font-weight: bold;">Submitted</div>
                    </div>
                </div>
            </div>
            
            <div class="footer">
                <p><strong>DRC Embassy Nairobi</strong></p>
                <p>Riverside Drive, Nairobi, Kenya</p>
                <p>Email: info@drc-embassy-kenya.org | Phone: +254-20-XXXXXXX</p>
                <p>This document was generated on ' . date("F j, Y \a\t g:i A") . '</p>
            </div>
        </div>
    </body>
    </html>';
    
    return $html;
}

// Function to download the PDF
function downloadVisaPDF($data) {
    $html = generateVisaPDF($data);
    
    // Set headers for file download
    header('Content-Type: text/html');
    header('Content-Disposition: attachment; filename="visa_application_summary_' . date('Y-m-d_H-i-s') . '.html"');
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');
    
    // Add instructions at the top of the HTML
    $instructions = '
    <div style="background: #e3f2fd; border: 1px solid #2196f3; border-radius: 5px; padding: 15px; margin-bottom: 20px; color: #1976d2;">
        <h3 style="margin: 0 0 10px 0; color: #1976d2;">📄 How to Save as PDF:</h3>
        <ol style="margin: 0; padding-left: 20px;">
            <li>Open this file in your web browser</li>
            <li>Press <strong>Ctrl + P</strong> (Windows) or <strong>Cmd + P</strong> (Mac)</li>
            <li>Select "Save as PDF" as the destination</li>
            <li>Click "Save" to download your PDF copy</li>
        </ol>
    </div>';
    
    // Insert instructions after the opening body tag
    $html = str_replace('<body>', '<body>' . $instructions, $html);
    
    echo $html;
    exit();
}
?>
