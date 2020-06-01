using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.SceneManagement;

public class Experiment : MonoBehaviour
{
    // Start is called before the first frame update
    void Start()
    {
        
    }

    // Update is called once per frame
    void Update()
    {
        
    }

    // Go to Focal Length Experiment Screen
    public void GoToFocalLength()
    {
        SceneManager.LoadScene(4);
    }

    // Go to Magnifying Glass Experiment Screen
    public void GoToMagnifyingGlass()
    {
        SceneManager.LoadScene(4);
    }

    // Go to Water Refraction Experiment Screen
    public void GoToWaterRefraction()
    {
        SceneManager.LoadScene(4);
    }

    // Go to Prism Dispersion Experiment Screen
    public void GoToPrismDispersion()
    {
        SceneManager.LoadScene(4);
    }

    // Go to Law of Reflection Experiment Screen
    public void GoToLawOfReflection()
    {
        SceneManager.LoadScene(7);
    }

    // Back
    public void Back()
    {
        UnityEngine.SceneManagement.SceneManager.LoadScene(0);
    }
}
