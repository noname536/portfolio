using System;
using System.Windows.Forms;

namespace Breakout
{
    public partial class LevelSelect : Form
    {
        private Level1 _form;
        private Level2 _form2;
        public LevelSelect()
        {
            InitializeComponent();
        }


        private void ShowForm(Form form)
        {
            if (form == null) 
                throw new ArgumentNullException(nameof(form));
            
            form.Visible = true;
        }

        private void Level1_Click(object sender, EventArgs e)
        {
            _form = new Level1();
            ShowForm(_form);
            Close();
        }
    
        private void Level2_Click(object sender, EventArgs e)
        {
            _form2 = new Level2();
            ShowForm(_form2);
            Close();
        }

        private void ExitGame_Click(object sender, EventArgs e)
        {
            Close();
        }
    }
}
