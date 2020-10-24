using UnityEngine;

public class GeoGPSReading : GeoSensorReading
{
    public float latitude;
    public float longitude;
    public float altitude;
    public float distance;
    public float speed;
    public Vector2 heading;

    public override string ToString()
    {
        return "GPS : " + "Lat:" + latitude + " Lon: " + longitude + "\nDistance: " + distance + " Speed: " + speed + " / " + timestampToString();
    }
}