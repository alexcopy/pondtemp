

#include "ESP8266WiFi.h"

extern "C" {
#include "c_types.h"
#include "ets_sys.h"
#include "os_type.h"
#include "osapi.h"
#include "mem.h"
#include "user_interface.h"
#include "smartconfig.h"
#include "lwip/opt.h"
#include "lwip/err.h"
#include "lwip/dns.h"
}

#include "debug.h"

// -----------------------------------------------------------------------------------------------------------------------
// ---------------------------------------------------------- Debug ------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------------


/**
 * Output WiFi settings to an object derived from Print interface (like Serial).
 * @param p Print interface
 */
void ESP8266WiFiClass::printDiag(Print& p) {
    const char* modes[] = { "NULL", "STA", "AP", "STA+AP" };
    p.print("Mode: ");
    p.println(modes[wifi_get_opmode()]);

    const char* phymodes[] = { "", "B", "G", "N" };
    p.print("PHY mode: ");
    p.println(phymodes[(int) wifi_get_phy_mode()]);

    p.print("Channel: ");
    p.println(wifi_get_channel());

    p.print("AP id: ");
    p.println(wifi_station_get_current_ap_id());

    p.print("Status: ");
    p.println(wifi_station_get_connect_status());

    p.print("Auto connect: ");
    p.println(wifi_station_get_auto_connect());

    struct station_config conf;
    wifi_station_get_config(&conf);

    const char* ssid = reinterpret_cast<const char*>(conf.ssid);
    p.print("SSID (");
    p.print(strlen(ssid));
    p.print("): ");
    p.println(ssid);

    const char* passphrase = reinterpret_cast<const char*>(conf.password);
    p.print("Passphrase (");
    p.print(strlen(passphrase));
    p.print("): ");
    p.println(passphrase);

    p.print("BSSID set: ");
    p.println(conf.bssid_set);

}

ESP8266WiFiClass WiFi;