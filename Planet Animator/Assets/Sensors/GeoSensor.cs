using System;
using System.Collections.Generic;
using System.Threading;
using UnityEngine;
using System.Collections;



public enum SensorType
{
    GPS, Gyroscope, Accelerometer, Magnetometer, Barometer, HeartMonitor, Default,
    WatchOrientation,
    WatchTouch,
    Microphone
}
public class GeoSensor
{
    private List<GeoSensorReading> _readings = new List<GeoSensorReading>();
    private const int _maxReadingsSize = 1000;
    private List<Action<GeoSensorReading>> _callbacks = new List<Action<GeoSensorReading>>();

    public GeoSensor()
    {
        
    }
    public virtual bool Poll()
    {
        return false;
    }


    public virtual void Stop()
    {
        return ;
    }

    public void AddReading(GeoSensorReading r)
    {
        if (_readings.Count >= _maxReadingsSize)
        {
            _readings.RemoveAt(0);
        }
        _readings.Add(r);
        foreach (var callback in _callbacks)
        {
            callback(r);
        }
    }

    public virtual SensorType Type
    {
        get { return SensorType.Default; }
    }

    public List<GeoSensorReading> Readings
    {
        get { return _readings; }
    }

    public GeoSensorReading PollAndRead()
    {
        if (Poll())
            return GetLastReading();
        else
            return null;
    }

    public GeoSensorReading GetLastReading()
    {
        if (_readings.Count >= 1)
            return _readings[_readings.Count - 1];
        else
            return null;
    }

    public int MaxReadingsSize
    {
        get { return _maxReadingsSize; }
    }
}
