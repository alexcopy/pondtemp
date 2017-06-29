#include <Adafruit_BME280.h>
#include <Adafruit_Sensor.h>
#include <SoftwareSerial.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <DHT.h>
#include <IRremote.h>


// set the DHT Pin
#define DHTPIN 8
#define DHTTYPE DHT11
#define DST_IP "192.168.50.58"
#define ALTITUDE 53.0 // Altitude in sutton uk 


float temperature;
float humidity;
float pressure;

int tempPin = 0; //pond analog pin
bool backlght = true;
int prevSensorVal = 0;
bool pond = false;
int counter = 0;
float totalPond = 0; // the running total
float averagePond = 0; // the average
int RECV_PIN = 11;
bool status;


DHT dht(DHTPIN, DHTTYPE);
LiquidCrystal_I2C lcd(0x27, 20, 4); // set the LCD address to 0x27 for a 16 chars and 2 line display
SoftwareSerial esp8266(2, 3); // RX, TX
IRrecv irrecv(RECV_PIN);
decode_results results;
Adafruit_BME280 bme; // I2C


void setup() {

  status = bme.begin(0x76);
  lcd.init();
  lcd.backlight();
  dht.begin();
  Serial.begin(115200);
  esp8266.begin(115200);
  showBmeStatus(status);
  readEsp(); // read comport output from esp(wifi module)
  wifiModulePrepare();

  prevSensorVal = digitalRead(7);
  lcd.clear();
  irrecv.enableIRIn();
  pinMode(7, INPUT_PULLUP); //button
  pinMode(6, INPUT_PULLUP); //fltr3
  pinMode(5, INPUT_PULLUP); //pond level

}

void loop() {
  lcd.setCursor(0, 0);
  int sensorVal = digitalRead(7);
  showTempAndHumid();
  if (sensorVal != prevSensorVal) {
    pond = pond ? false : true;
    prevSensorVal = 1;
    lcd.clear();
    delay(500);
  }

  // отправлять на сервер каждые  counter / 10 = секунд
  if (counter > 600) {
    sendData();
    counter = 1;
    totalPond = 0;
    averagePond = 0;
    lcd.clear();
  }

  // if we get a IFR signal
  if (irrecv.decode(&results)) {
    irrecv.resume();
    delay(1000);
    translateIR();
  }
  backlght ? lcd.backlight() : lcd.noBacklight(); // lcd balcklight off/on
  totalPond += pondTemp();
  averagePond = totalPond / counter;

  prevSensorVal = digitalRead(7);
  delay(100);
  counter++;
}



void showTempAndHumid() {

  getBmePressure();
  getBmeHumidity();
  getBmeTemperature();

  if (pond == true) {
    lcd.setCursor(0, 0);
    lcd.print("StrTemp: ");
    lcd.print(dht.readTemperature());
    prntTemp();
    lcd.setCursor(0, 1);
    lcd.print("PndTemp: ");
    lcd.print(averagePond);
    prntTemp();
  } else {
    lcd.setCursor(0, 0);
    String temperatureString = String(temperature, 1);
    lcd.print("ShdTemp:");
    lcd.print(temperatureString);
    prntTemp();
    lcd.setCursor(0, 1);
    lcd.print("Press: ");
    String pressureString = String(pressure, 2);
    lcd.print(pressureString);
    lcd.print(" hPa");
  }

}

void prntTemp() {
  lcd.print((char) 223);
  lcd.print("C");
}

float pondTemp() {
  float val = analogRead(tempPin);
  float mv = (val / 1024.0) * 5000;
  return mv / 10;
}


void wifiModulePrepare() {
  lglcd("Starting WIFI...", 0);
  delay(2000);
  esp8266.println("AT+RST"); // сброс и проверка, если модуль готов
  readEsp();
  lglcd("Reseting WiFi...", 0);
  delay(2000);
  esp8266.println("AT+CWMODE=1");
  delay(2000);
  if (esp8266.available()) {
    Serial.println("WiFi - Module is ready");
    lglcd("WiFi - Module is ready", 0);

  } else {
    Serial.println("Module dosn't respond.");
    lglcd("Module dosn't respond.", 0);
    lcd.setCursor(1, 1);
    lcd.println("Please reset.");
    while (1);
  }

  delay(1000);
  // try to connect to wifi
  boolean connected = false;
  lcd.setCursor(0, 1);
  for (int i = 0; i < 10; i++) {
    if (connectWiFi()) {
      connected = true;
      lglcd("Wi-Fi connected.", 0);
      break;
    }
  }

  delay(2000);
  esp8266.println("AT+CIPMUX=0");
}

void lglcd(String txt, int i) {
  lcd.clear();
  lcd.setCursor(i, 0);
  txt.trim();
  lcd.println(txt);
}

bool connectWiFi() {
  lglcd("Conn to WIFI....", 0);
  readEsp();
  esp8266.println("AT+CWJAP=\"redkot\",\"Gorkogo177\"");
  delay(2000);
  return true;
}

String readEsp() { //rename to check for wifi status read
  String buf;
  while (esp8266.available()) {
    delay(50);
    // The esp has data so display its output to the serial window
    char c = esp8266.read(); // read the next character.
    buf += c;
    Serial.write(c);
    delay(50);
  }
  Serial.print(buf);
  return buf;
}


// ---------------START OF WIFI SECTION -----------------//

void sendData() {
  lglcd("Sending Data....", 0);
  prepToSend();
  delay(500);
  addHeaderAndSend(tempUrl(""));
}


void prepToSend() {
  esp8266.println("AT+CIPMUX=0");
  String cmd = "AT+CIPSTART=\"TCP\",\"";
  cmd += DST_IP;
  cmd += "\",80";
  esp8266.println(cmd);
}

String tempUrl(String cmd) {
  cmd = "GET /receiver?ptemp=";
  cmd += averagePond;
  cmd += "&shedtemp=";
  cmd +=  String(temperature, 1);
  cmd += "&strtemp=";
  cmd += String(temperature, 1);  // change to dht.readTemperature(); after DHT to be fixed
  cmd += "&shedhumid=";
  cmd +=  String(humidity, 0);
  cmd += "&streethumid=";
  cmd +=  dht.readHumidity();
  cmd += "&press=";
  cmd +=  String(pressure, 2);
  cmd += "&chkstr=";
  cmd +=  dht.readTemperature();
  cmd += "&fltr3=";
  cmd += digitalRead(6);
  cmd += "&pndlvl=";
  cmd += digitalRead(5);
  return cmd;
}

void addHeaderAndSend(String cmd) {
  cmd += " HTTP/1.0\r\n";
  cmd += "Host: pondtemp.m2mcom.ru\r\n\r\n";
  esp8266.print("AT+CIPSEND=");
  esp8266.println(cmd.length());
  delay(1000);
  esp8266.println(cmd);
  Serial.println(cmd);
  delay(5000);
  esp8266.println("\r\n");
  delay(1000);
  esp8266.println("AT+CIPCLOSE");
  delay(1000);
}

// ---------------END OF WIFI SECTION -----------------//

void translateIR() {
  switch (results.value) {
    case 0xFFA25D:
      backlght = backlght ? false : true;
      break;

    case 0xFF629D: //mode is a type of work CH

      break;

    case 0xFFE21D:
    
      break;

    case 0xFF22DD:
      break;

    case 0xFF02FD:
      break;

    case 0xFFC23D:
      break;

    case 0xFFE01F:
      break;

    case 0xFFA857:
      break;


    case 0xFF906F:
      break;
    default:
      break;
  }

  delay(500);
}

void ifrMode() {

  lglcd("Select mode:", 0);

  for (int i = 0; i < 100; i++) {
    if (irrecv.decode(&results)) {
      delay(100);
    }
    delay(100);
  }
}
//show BME status on LCD  based on return val status
void showBmeStatus(bool status) {
  if (!status) {
    lglcd("BME Error. Check", 0);
    lglcd("connections", 1);
    delay(4000);
    lglcd("Starts w/o BME", 1);
    delay(10000);

  } else {
    lcd.clear();
    lcd.print("BME is OK!");
    delay(1500);
  }
}

//BME Measurments
float getBmeTemperature()
{
  temperature = bme.readTemperature();
}

float getBmeHumidity()
{
  humidity = bme.readHumidity();
}

float getBmePressure()
{
  pressure = bme.readPressure();
  pressure = bme.seaLevelForAltitude(ALTITUDE, pressure);
  pressure = pressure / 100.0F;
}


int parseIfrNum() {
  switch (results.value)
  {
    case 0xFF6897:
      return 0;
      break;

    case 0xFF9867:
      return 100;
      break;

    case 0xFFB04F:
      return 200;
      break;

    case 0xFF30CF:
      return 1;
      break;

    case 0xFF18E7:
      return 2;
      break;

    case 0xFF7A85:
      return 3;
      break;

    case 0xFF10EF:
      return 4;
      break;

    case 0xFF38C7:
      return 5;
      break;

    case 0xFF5AA5:
      return 6;
      break;

    case 0xFF42BD:
      return 7;
      break;

    case 0xFF4AB5:
      return  8;
      break;

    case 0xFF52AD:
      return 9;
      break;

  }
  return 1000;
}




