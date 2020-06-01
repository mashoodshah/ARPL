using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.SceneManagement;

public class FocalLengthMenu : MonoBehaviour
{
    // Start is called before the first frame update
    void Start()
    {

    }

    // Update is called once per frame
    void Update()
    {

    }

    // Go to Given Scene Number
    public void GoToScreen(int n)
    {
        UnityEngine.SceneManagement.SceneManager.LoadScene(n);
    }
}
