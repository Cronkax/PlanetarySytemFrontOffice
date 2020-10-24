using UnityEngine;
using UnityEngine.UI;

class SensorController : MonoBehaviour
{
    //Sensor GPS
    private GeoGPSSensor gpsSensor;
    //Sensor bússola
    private GeoCompassSensor compassSensor;
    //Sensor acelerómetro
    private GeoAccSensor accSensor;
    //Sensor giroscópio
    private GeoGyrSensor gyrSensor;

    [SerializeField]
    //Texto GPS
    public Text gpsText;
    [SerializeField]
    //Texto bússola
    public Text compassText;
    [SerializeField]
    //Texto acelerómetro
    public Text accText;
    [SerializeField]
    //Texto giroscópio
    public Text gyrText;

    public void Start()
    {
        gpsSensor = new GeoGPSSensor();
        compassSensor = new GeoCompassSensor();
        accSensor = new GeoAccSensor();
        gyrSensor = new GeoGyrSensor();
    }

    public void Update()
    {
        GeoSensorReading aux = gpsSensor.PollAndRead();

        //GPS
        if (aux == null)
            gpsText.text = "GPS is off";
        else
            gpsText.text = aux.ToString();

        aux = compassSensor.PollAndRead();

        //Bússola
        if (aux == null)
            compassText.text = "No compass on this device";
        else
            compassText.text = aux.ToString();

        aux = accSensor.PollAndRead();

        //Acelerómetro
        if (aux == null) 
            accText.text = "No accelerometer on this device";
        else
            accText.text = aux.ToString();

        aux = gyrSensor.PollAndRead();

        //Giroscópio
        if (aux == null)
            gyrText.text = "No gyroscope on this device";
        else
            gyrText.text = aux.ToString();
    }
}
