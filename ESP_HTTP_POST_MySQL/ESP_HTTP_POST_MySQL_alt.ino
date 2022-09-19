
#ifdef ESP32
  #include <WiFi.h>
  #include <HTTPClient.h>
#else
  #include <ESP8266WiFi.h>
  #include <ESP8266HTTPClient.h>
  #include <WiFiClient.h>
#endif

#include <Wire.h>
#include <Adafruit_Sensor.h>
#include <Adafruit_BME280.h>
#include <Adafruit_GFX.h>      // include Adafruit graphics library
#include <Adafruit_SSD1306.h>  // include Adafruit SSD1306 OLED display driver
#include <Fonts/FreeSans9pt7b.h> 

const char* ssid        = "";
const char* password    = "";
//const char* ssid        = "";
//const char* password    = "";
const char* serverName  = "";
String apiKeyValue      = "";
String sensorId         = "1";

Adafruit_SSD1306  display(128, 32, &Wire, -1, 400000UL, 100000UL);
Adafruit_BME280   bme;  // I2C

#define BME280_I2C_ADDRESS  0x76

unsigned long lastTime    = 0;
unsigned long timerDelay  = 600000-1000;

float temp, humi, pres;

void setup() {
  Serial.begin(115200);

  delay(1000);  // wait a second

  // initialize the SSD1306 OLED display with I2C address = 0x3D
  display.begin(SSD1306_SWITCHCAPVCC, 0x3C);

  // clear the display buffer.
  display.clearDisplay();

  //display.setTextSize(1);   // Normal 1:1 pixel scale
  display.setTextColor(WHITE, BLACK);  // set text color to white and black background
  display.setFont(&FreeSans9pt7b);
  display.dim(true);

  display.setTextWrap(false);           // disable text wrap
  display.display();        // update the display

  connect_Wifi();

  // (you can also pass in a Wire library object like &Wire2)
  bool status = bme.begin(BME280_I2C_ADDRESS);
  if (!status) {
    Serial.println("Could not find a valid BME280 sensor, check wiring or change I2C address!");
    while (1);
  }
  
  Serial.print("Timer set to ");
  Serial.print(timerDelay/1000/60);
  Serial.print(" minutes (timerDelay variable), it will take ");
  Serial.print(timerDelay/1000/60);
  Serial.println(" minutes before publishing the first reading.");
}


void connect_Wifi()  {

  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
  
}

void loop() {

  temp = bme.readTemperature();    // get temperature in degree Celsius
  humi = bme.readHumidity();       // get humidity in rH%
  pres = bme.readPressure();       // get pressure in Pa


  if ((millis() - lastTime) > timerDelay) {
    
    //Check WiFi connection status
    if(WiFi.status()== WL_CONNECTED){
      
      WiFiClient client;
      HTTPClient http;

      // Your Domain name with URL path or IP address with path
      http.begin(client, serverName);

      // Specify content-type header
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");

      // Prepare your HTTP POST request data
      String httpRequestData = "api_key=" + apiKeyValue 
                             + "&fk_Sensor=" + sensorId
                             + "&temperature=" + String(temp)
                             + "&humidity=" + String(humi) 
                             + "&pressure=" + String(pres/100.0F) 
                             + "";
      Serial.print("httpRequestData: ");
      Serial.println(httpRequestData);


      int httpResponseCode = http.POST(httpRequestData);

      if (httpResponseCode>0) {
        Serial.print("HTTP Response code: ");
 
      }
      else {
        Serial.print("Error code: ");

      }
      Serial.println(httpResponseCode);
      http.end();
    }
    else {
      Serial.println("WiFi Disconnected...reconnecting");
      connect_Wifi();
    }
    
    lastTime = millis();
    
  }

  display.clearDisplay();
  
  display.setCursor(0,12);
  display.print("T: ");
  display.print(temp,2);
  //display.print((char)247);
  display.drawCircle(72, 2, 2, WHITE);
  display.print("  C");
 
  display.setCursor(0,30);
  display.print("H: ");
  display.print(humi,2);
  display.print("%");

  display.display();

  delay(1000);  // wait a second
  
}
