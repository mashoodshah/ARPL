using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.SceneManagement;

public class Home : MonoBehaviour
{
    // Start is called before the first frame update
    void Start()
    {
        
    }

    // Update is called once per frame
    void Update()
    {
        
    }

    // Go to Signup Screen
    public void GoToSignup() {
        SceneManager.LoadScene(1);
    }

    // Go to Signin Screen
    public void GoToSignin()
    {
        SceneManager.LoadScene(2);
    }

    //  Quit Application
    public void Exit()
    {
        Application.Quit();
    }
}
