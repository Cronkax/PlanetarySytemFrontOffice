using UnityEngine;

public class GeoCompassReading : GeoSensorReading
{
    public float magneticHeading;

    public override string ToString()
    {
        return "Compass : " + magneticHeading + " / " + timestampToString();
    }
}
