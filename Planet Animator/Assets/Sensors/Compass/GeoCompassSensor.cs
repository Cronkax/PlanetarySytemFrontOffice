using UnityEngine;

public class GeoCompassSensor : GeoSensor
{
    public override bool Poll()
    {
        Input.compass.enabled = true;
        float magneticHeading = Input.compass.magneticHeading;
        GeoCompassReading reading = new GeoCompassReading
        {
            magneticHeading = magneticHeading
        };
        AddReading(reading);
        return true;
    }
}
