#include <WiFi.h>
#include <HTTPClient.h>
#include <MFRC522.h>
#include <SPI.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>

// Replace these with your network credentials
const char* ssid = "Js'Media";
const char* password = "password";

// The URL of the PHP endpoint
const char* serverUrl = "http://192.168.60.52/readrfid/api/rfid.php";

// RC522 connections
#define SS_PIN    21  // Define your SS pin
#define RST_PIN   22  // Define your RST pin
MFRC522 rfid(SS_PIN, RST_PIN);

// LED and Buzzer Configuration
#define RED_LED_PIN    14   // Red LED pin
#define GREEN_LED_PIN  12   // Green LED pin
#define BUZZER_PIN     13   // Buzzer pin

// I2C Configuration (Custom Pins)
#define I2C_SDA 4  // Custom SDA pin
#define I2C_SCL 16 // Custom SCL pin

// LCD Configuration
#define LCD_ADDRESS 0x27 // I2C address for the LCD
#define LCD_COLUMNS 16
#define LCD_ROWS    2

LiquidCrystal_I2C lcd(LCD_ADDRESS, LCD_COLUMNS, LCD_ROWS);

void setup() {
  Serial.begin(115200);
  Serial.println("Initializing...");

  // Initialize SPI bus
  SPI.begin();

  // Initialize RC522
  rfid.PCD_Init();
  Serial.println("RFID reader initialized");

  // Initialize LEDs, Buzzer, and LCD
  pinMode(RED_LED_PIN, OUTPUT);
  pinMode(GREEN_LED_PIN, OUTPUT);
  pinMode(BUZZER_PIN, OUTPUT);

  // Initialize LCD
  Wire.begin(I2C_SDA, I2C_SCL); // Initialize I2C with custom pins
  lcd.init();
  lcd.backlight();
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Initializing...");
  
  // Connect to WiFi
  WiFi.begin(ssid, password);

  // Blink red LED while connecting to Wi-Fi
  while (WiFi.status() != WL_CONNECTED) {
    digitalWrite(RED_LED_PIN, HIGH);
    delay(250);
    digitalWrite(RED_LED_PIN, LOW);
    delay(250);
  }
  // Once connected
  digitalWrite(RED_LED_PIN, HIGH);
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Connected!");
  delay(2000); // Show message for 2 seconds
}

void loop() {
  // Check if a tag is available
  if (rfid.PICC_IsNewCardPresent() && rfid.PICC_ReadCardSerial()) {
    Serial.println("Found an RFID tag!");

    // Read UID
    String uidStr = "";
    for (byte i = 0; i < rfid.uid.size; i++) {
      uidStr += String(rfid.uid.uidByte[i] < 0x10 ? "0" : "");
      uidStr += String(rfid.uid.uidByte[i], HEX);
    }
    uidStr.toUpperCase(); // Make UID uppercase

    // Print UID to Serial Monitor
    Serial.println("UID: " + uidStr);

    // Send UID to the PHP endpoint
    HTTPClient http;
    http.begin(serverUrl);
    http.addHeader("Content-Type", "application/json");

    // Create JSON payload
    String payload = "{\"uid\": \"" + uidStr + "\"}";
    int httpResponseCode = http.POST(payload);

    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("Response: " + response);
    } else {
      Serial.println("Error: " + String(httpResponseCode));
    }

    http.end();

    // Sound buzzer and light green LED
    digitalWrite(BUZZER_PIN, HIGH);
    digitalWrite(GREEN_LED_PIN, HIGH);
    delay(500); // Buzzer and green LED duration
    digitalWrite(BUZZER_PIN, LOW);
    digitalWrite(GREEN_LED_PIN, LOW);

    // Display UID on LCD
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Tag Scanned:");
    lcd.setCursor(0, 1);
    lcd.print(uidStr);

    // Halt PICC and stop encryption on PCD
    rfid.PICC_HaltA();
    rfid.PCD_StopCrypto1();
  } else {
    Serial.println("No tag found");
  }

  delay(1000); // Wait a second before scanning again
}