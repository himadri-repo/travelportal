        <!-- Sidebar Holder -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Manage Account</h3>
                <strong>MA</strong>
            </div>

            <ul class="list-unstyled components">
                <?php 
                // active
                $lastcat="NA";
                $class = ($lastcat=="NA"?"active":"");

                foreach ($modules as $module) { ?>
                    <?php 
                        $cat = str_replace(" ", "_", trim($module["category"]));
                        $code = $module["code"];
                        $link=strtolower($cat).'_'.$code;
                        if($lastcat!=$module["category"] && !empty($module["category"])) { 
                            if($lastcat!="NA") { ?>
                                </ul>
                            </li>
                            <?php    
                            }
                            ?>
                            <li class="<?php echo $class; ?>">
                                <a href="#<?php echo $link?>" data-toggle="collapse" aria-expanded="false">
                                    <i class="glyphicon glyphicon-home"></i><?php echo $module["category"]?>
                                </a>
                                <ul class="collapse list-unstyled" id="<?php echo $link?>">
                        <?php
                        }
                        ?>
                            <li><a href="#"><?php echo $module["display_name"]?></a></li>
                            <?php 
                            $lastcat = $module["category"];
                            $class = ($lastcat=="NA"?"active":"");
                            ?>
                <?php
                }
                ?>
            </ul>
        </nav>
        <!-- End of Sidebar Holder -->
