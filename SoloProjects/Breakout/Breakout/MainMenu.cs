using System;
using System.Windows.Forms;

namespace Breakout
{
    public partial class MainMenu : Form
    {
        public MainMenu()
        {
            InitializeComponent();

        }

        private void startGame_Click(object sender, EventArgs e)
        {
            new LevelSelect {Visible = true};
            hideForm(this);
        }


        private void hideForm(Form form)
        {
            form.FormBorderStyle = FormBorderStyle.None;
            form.Height = 0;
            form.Width = 0;
            form.Visible = false;
        }

        private void ExitGameButton_Click(object sender, EventArgs e)
        {
            Close();
        }
    }
}
